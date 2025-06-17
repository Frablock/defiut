import requests
from requests.packages.urllib3.exceptions import InsecureRequestWarning

def test_endpoint(url, expected_keys, expected_data=None, method='GET', payload=None, headers=None, json_body=False):
    """
    Teste un endpoint API.

    Args:
        url (str): L'URL de l'endpoint à tester.
        expected_keys (list): Liste des clés attendues dans la réponse.
        expected_data (dict, optional): Dictionnaire des paires clé-valeur attendues. Par défaut, None.
        method (str, optional): Méthode HTTP à utiliser ('GET', 'POST', etc.). Par défaut, 'GET'.
        payload (dict, optional): Données à envoyer avec la requête (pour POST, PUT, etc.). Par défaut, None.
        headers (dict, optional): En-têtes HTTP à inclure dans la requête. Par défaut, None.
        json_body (bool, optional): Si True, envoie payload comme JSON dans le corps de la requête. Par défaut, False.

    Returns:
        bool: True si le test passe, False sinon.
    """
    try:
        global site
        # Désactiver les avertissements SSL
        requests.packages.urllib3.disable_warnings(InsecureRequestWarning)

        url = site + url

        # Préparer les headers
        if headers is None:
            headers = {}
        if json_body and payload:
            headers['Content-Type'] = 'application/json'

        # Faire la requête
        if method.upper() == 'GET':
            response = requests.get(url, verify=False, headers=headers)
        elif method.upper() == 'POST':
            if json_body and payload:
                response = requests.post(url, json=payload, verify=False, headers=headers)
            else:
                response = requests.post(url, data=payload, verify=False, headers=headers)
        elif method.upper() == 'PUT':
            if json_body and payload:
                response = requests.put(url, json=payload, verify=False, headers=headers)
            else:
                response = requests.put(url, data=payload, verify=False, headers=headers)
        elif method.upper() == 'DELETE':
            response = requests.delete(url, verify=False, headers=headers)
        else:
            raise ValueError(f"Unsupported HTTP method: {method}")

        response.raise_for_status()
        data = response.json()

        # Vérifier les clés
        # La structure de la réponse peut être différente, donc ajustons la vérification
        if "data" in data:
            response_data = data["data"]
        else:
            response_data = data

        missing_keys = [key for key in expected_keys if key not in response_data]
        if missing_keys:
            print(f"Error: Missing keys in response for {url}: {missing_keys}")
            return False

        # Vérifier les valeurs si expected_data est fourni
        if expected_data:
            for key, value in expected_data.items():
                if key in response_data and response_data[key] != value:
                    print(f"Error: For {url}, expected {key} to be {value}, got {response_data[key]}")
                    return False

        print(f"Test passed for {url}")
        return data

    except requests.exceptions.SSLError as e:
        print(f"SSL Error for {url}: {e}")
        print("If you are using HTTPS with a self-signed certificate, try setting verify=False")
        return False
    except requests.exceptions.RequestException as e:
        print(f"Error making request to {url}: {e}")
        return False
    except ValueError as e:
        print(f"Error: Response from {url} is not valid JSON. {e}")
        return False

def run_tests(test_cases):
    """
    Exécute une série de tests sur différents endpoints.

    Args:
        test_cases (list): Liste de dictionnaires, chaque dictionnaire représentant un test.
                           Chaque dictionnaire doit contenir au moins 'url' et 'expected_keys'.
                           Optionnellement, il peut contenir 'expected_data', 'method', 'payload', 'headers' et 'json_body'.
    """
    global auth_token
    auth_token = None
    password_reset_required = False

    for test_case in test_cases:
        url = test_case['url']
        expected_keys = test_case['expected_keys']
        expected_data = test_case.get('expected_data', None)
        method = test_case.get('method', 'GET')
        payload = test_case.get('payload', None)
        headers = test_case.get('headers', {})
        json_body = test_case.get('json_body', False)

        # Utiliser le token d'authentification si disponible
        if auth_token and 'Authorization' not in headers:
            headers['Authorization'] = auth_token

        print(f"Testing {url}...")
        result = test_endpoint(url, expected_keys, expected_data, method, payload, headers, json_body)

        # Gestion des tokens
        if result and isinstance(result, dict):
            if 'data' in result and 'token' in result['data']:
                auth_token = result['data']['token']
                print(f"Token obtenu: {auth_token}")
            elif 'token' in result:
                auth_token = result['token']
                print(f"Token obtenu: {auth_token}")

        # Marquer pour réinitialisation du mot de passe
        if 'change_password' in url and result:
            password_reset_required = True

        print("---")

    # Réinitialiser le mot de passe après les tests
    if password_reset_required:
        print("Réinitialisation du mot de passe...")
        reset_payload = {
            'usermail': 'francois.patinec@defiut.com',
            'password': 'password2new',
            'new_password': 'password2'
        }
        headers = {'Authorization': auth_token} if auth_token else {}
        test_endpoint(
            '/api/change_password',
            ['changed_password'],
            method='POST',
            payload=reset_payload,
            headers=headers,
            json_body=True
        )
        print("Mot de passe réinitialisé avec succès")

if __name__ == "__main__":

    site = "http://localhost:80"

    # Les cas de test
    test_cases = [
        # tests pour l'authentification
        {
            'url': '/api/login',
            'method': 'POST',
            'payload': {
                'usermail': 'francois.patinec@defiut.com',
                'password': 'password2'
            },
            'expected_keys': ['token', 'expirationDate'],
            'json_body': True
        },
        {
            'url': '/api/token_validity_test',
            'method': 'POST',
            'expected_keys': ['message'],
            'json_body': True
        },
        {
            'url': '/api/change_password',
            'method': 'POST',
            'payload': {
                'usermail': 'francois.patinec@defiut.com',
                'password': 'password2',
                'new_password': 'password2new'
            },
            'expected_keys': ['changed_password'],
            'expected_data': {'changed_password': 'ok'},
            'json_body': True
        },
        {
            'url': '/api/logout',
            'method': 'POST',
            'expected_keys': ['message'],
            'expected_data': {'message': 'Logged out successfully'},
            'json_body': True
        },
        {
            'url': '/api/login',
            'method': 'POST',
            'payload': {
                'usermail': 'francois.patinec@defiut.com',
                'password': 'password2new'
            },
            'expected_keys': ['token', 'expirationDate'],
            'json_body': True
        },
        {
            'url': '/api/change_password',
            'method': 'POST',
            'payload': {
                'usermail': 'francois.patinec@defiut.com',
                'password': 'password2new',
                'new_password': 'password2'
            },
            'expected_keys': ['changed_password'],
            'expected_data': {'changed_password': 'ok'},
            'json_body': True
        },
    ]

    # Exécuter les tests
    run_tests(test_cases)

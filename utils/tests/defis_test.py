import requests
from requests.packages.urllib3.exceptions import InsecureRequestWarning

def test_endpoint(url, expected_keys, expected_data=None, method='GET', payload=None, headers=None):
    """
    Teste un endpoint API.

    Args:
        url (str): L'URL de l'endpoint à tester.
        expected_keys (list): Liste des clés attendues dans la réponse.
        expected_data (dict, optional): Dictionnaire des paires clé-valeur attendues. Par défaut, None.
        method (str, optional): Méthode HTTP à utiliser ('GET', 'POST', etc.). Par défaut, 'GET'.
        payload (dict, optional): Données à envoyer avec la requête (pour POST, PUT, etc.). Par défaut, None.
        headers (dict, optional): En-têtes HTTP à inclure dans la requête. Par défaut, None.

    Returns:
        bool: True si le test passe, False sinon.
    """
    try:
        global site
        # Désactiver les avertissements SSL
        requests.packages.urllib3.disable_warnings(InsecureRequestWarning)

        url = site + url

        # Faire la requête
        if method.upper() == 'GET':
            response = requests.get(url, verify=False, headers=headers)
        elif method.upper() == 'POST':
            response = requests.post(url, json=payload, verify=False, headers=headers)
        elif method.upper() == 'PUT':
            response = requests.put(url, json=payload, verify=False, headers=headers)
        elif method.upper() == 'DELETE':
            response = requests.delete(url, verify=False, headers=headers)
        else:
            raise ValueError(f"Unsupported HTTP method: {method}")

        response.raise_for_status()
        data = response.json()

        # Vérifier les clés
        missing_keys = [key for key in expected_keys if key not in data]
        if missing_keys:
            print(f"Error: Missing keys in response for {url}: {missing_keys}")
            return False

        # Vérifier les valeurs si expected_data est fourni
        if expected_data:
            for key, value in expected_data.items():
                if key in data and data[key] != value:
                    print(f"Error: For {url}, expected {key} to be {value}, got {data[key]}")
                    return False

        print(f"Test passed for {url}")
        return True

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
                           Optionnellement, il peut contenir 'expected_data', 'method', 'payload', et 'headers'.
    """
    for test_case in test_cases:
        url = test_case['url']
        expected_keys = test_case['expected_keys']
        expected_data = test_case.get('expected_data', None)
        method = test_case.get('method', 'GET')
        payload = test_case.get('payload', None)
        headers = test_case.get('headers', None)

        print(f"Testing {url}...")
        success = test_endpoint(url, expected_keys, expected_data, method, payload, headers)
        if not success:
            print(f"Test failed for {url}")
        print("---")

if __name__ == "__main__":

    site = "http://localhost:80"

    # Les cas de test
    test_cases = [
        {
            'url': '/api/defis/1',
            'expected_keys': ["nom", "description", "pointsRecompense", "difficulte", "user", "tags", "fichiers"],
            'expected_data': {
                "nom": "Le commit perdu",
                "pointsRecompense": 215,
                "tags": ["Git", "Commits"]
            }
        },
        {
            'url': '/api/defis/2',
            'expected_keys': ["nom", "description", "pointsRecompense", "difficulte", "user", "tags", "fichiers"],
            'expected_data': {
                "nom": "Hydre de Lerne",
                "pointsRecompense": 350,
                "tags": ["Java", "Reverse"]
            }
        },

    ]

    # Exécuter les tests
    run_tests(test_cases)

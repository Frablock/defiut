import requests
from requests.packages.urllib3.exceptions import InsecureRequestWarning

def test_endpoint(url, expected_keys, expected_data=None, method='GET', payload=None, headers=None, json_body=False):
    try:
        global site
        requests.packages.urllib3.disable_warnings(InsecureRequestWarning)
        url = site + url
        if headers is None:
            headers = {}
        if json_body and payload:
            headers['Content-Type'] = 'application/json'
        if method.upper() == 'GET':
            response = requests.get(url, verify=False, headers=headers)
        elif method.upper() == 'POST':
            if json_body and payload:
                response = requests.post(url, json=payload, verify=False, headers=headers)
            else:
                response = requests.post(url, data=payload, verify=False, headers=headers)
        else:
            raise ValueError(f"Unsupported HTTP method: {method}")
        response.raise_for_status()
        data = response.json()
        # Vérification des clés attendues
        for key in expected_keys:
            if key not in data:
                print(f"Error: Missing key '{key}' in response for {url}")
                return False
        print(f"Test passed for {url}")
        print("Response data:", data)
        return data
    except Exception as e:
        print(f"Test failed for {url}: {e}")
        return False

def run_tests(test_cases):
    global site
    for test_case in test_cases:
        url = test_case['url']
        expected_keys = test_case['expected_keys']
        method = test_case.get('method', 'GET')
        payload = test_case.get('payload', None)
        headers = test_case.get('headers', {})
        json_body = test_case.get('json_body', False)
        print(f"\nTesting {url} with payload {payload} ...")
        test_endpoint(url, expected_keys, method=method, payload=payload, headers=headers, json_body=json_body)

if __name__ == "__main__":
    site = "http://localhost:80"

    test_cases = [
        # Test 1 : Tous les défis avec le tag "Git"
        {
            'url': '/api/defis/filter',
            'method': 'POST',
            'payload': {
                'tags': ['Git'],
                'category': None
            },
            'expected_keys': ['error', 'data', 'error_message'],
            'json_body': True
        },
        # Test 2 : Défis de la catégorie "Web" avec le tag "SQL"
        {
            'url': '/api/defis/filter',
            'method': 'POST',
            'payload': {
                'tags': ['SQL'],
                'category': 'Web'
            },
            'expected_keys': ['error', 'data', 'error_message'],
            'json_body': True
        },
        # Test 3 : Défis de la catégorie "Base de donnée" avec le tag "Base de donnée"
        {
            'url': '/api/defis/filter',
            'method': 'POST',
            'payload': {
                'tags': ['Base de donnée'],
                'category': 'Base de donnée'
            },
            'expected_keys': ['error', 'data', 'error_message'],
            'json_body': True
        },
        # Test 4 : Défis de la catégorie "Algorithmie" avec le tag "Optimisation"
        {
            'url': '/api/defis/filter',
            'method': 'POST',
            'payload': {
                'tags': ['Optimisation'],
                'category': 'Algorithmie'
            },
            'expected_keys': ['error', 'data', 'error_message'],
            'json_body': True
        },
        # Test 5 : Tous les défis avec le tag "Injection"
        {
            'url': '/api/defis/filter',
            'method': 'POST',
            'payload': {
                'tags': ['Injection'],
                'category': None
            },
            'expected_keys': ['error', 'data', 'error_message'],
            'json_body': True
        },
        # Test 6 : Tous les défis avec le tag "Java"
        {
            'url': '/api/defis/filter',
            'method': 'POST',
            'payload': {
                'tags': ['Java'],
                'category': None
            },
            'expected_keys': ['error', 'data', 'error_message'],
            'json_body': True
        },
        # Test 7 : Tous les défis sans filtre (doit retourner tous les défis)
        {
            'url': '/api/defis/filter',
            'method': 'POST',
            'payload': {
                'tags': [],
                'category': None
            },
            'expected_keys': ['error', 'data', 'error_message'],
            'json_body': True
        },
    ]
    run_tests(test_cases)

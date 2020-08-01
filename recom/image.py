import requests, json 
import argparse

ap = argparse.ArgumentParser()
ap.add_argument('-i', '--image', required=True,
                help = 'path to input image')
args = ap.parse_args()

BASE_URI = 'https://api.cognitive.microsoft.com/bing/v7.0/images/visualsearch'
SUBSCRIPTION_KEY = '5e1b7dc374424e198df5511161262bc3'
imagePath = args.image
HEADERS = {'Ocp-Apim-Subscription-Key': SUBSCRIPTION_KEY}
file = {'image' : ('myfile', open(imagePath, 'rb'))}

def print_json(obj):
    """Print the object as json"""
    print(json.dumps(obj, sort_keys=True, indent=2, separators=(',', ': ')))
try:
    response = requests.post(BASE_URI, headers=HEADERS, files=file, json={"knowledgeRequest":{"filters":{"site":"www.realestate.com.au"}}})
    response.raise_for_status()
    print_json(response.json())
    
except Exception as ex:
    raise ex

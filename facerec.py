import sys
import base64
from azure.cognitiveservices.vision.face import FaceClient
from azure.cognitiveservices.vision.face.operations import FaceListOperations
from msrest.authentication import CognitiveServicesCredentials


email = sys.argv[1]
image = sys.argv[2]
fun = sys.argv[3]


img_source = base64.b64decode((image))
API_KEY = 'd05adbed3733426eb4077c7e9df55b1e'
ENDPOINT = 'https://mefaceapi.cognitiveservices.azure.com/'

face_client = FaceClient(ENDPOINT, CognitiveServicesCredentials(API_KEY))
face_list_id = "secure-facelist"

similar_faces = []
response_detection = face_client.face.detect_with_stream(
    image=img_source,
    detection_model='detection_01',
    recognition_model='recognition_04'
)
if (response_detection.size() == 0):
    print("No Face Detected. Try again.")
elif (response_detection.size() > 1):
    print("Multiple Face Detected. Try again.")

else:
    if (fun == '1'):
        try:
            face_client.face_list.create(
                face_list_id=face_list_id, recognition_model='recognition_04', name=face_list_id)
        except:
            pass
        face_client.face_list.add_face_from_stream(face_list_id=face_list_id,
                                                   image=img_source,
                                                   detection_model='detection_01',
                                                   user_data=email)
        print("Account Created successfully!!")

    else:
        try:
            similar_faces = face_client.face.find_similar(
                response_detection[0].face_id, "secure-facelist")
        except:
            pass

        if not similar_faces:
            print("Face does not match. Try again.")
        else:
            flag = 0
            for face in similar_faces:
                if (face.user_data == email):
                    print("Logged in successfully.")
                    flag = 1
            if flag == 0:
                print("Face does not match. Try again.")

import CV.labelCV as lc
import CV.logoCV as lg
import CV.ocrCV as oc
import CV.placeCV as pc
import subprocess
import DB.dataController as dc
import service.util as ut

event = dict()
location = dict()
#err_msg sends error feedback
err_msg = ""
#set google credential to app instance
def setCredential():
    #must be changed to ~/GoogleAPIs/Hackatown2018-3dd19556fe93.json on server
    msg = subprocess.check_output('export GOOGLE_APPLICATION_CREDENTIALS=~/Desktop/GoogleAPIs/OAuth/Hackatown2018-3dd19556fe93.json',shell=True)       
    
def process_input(img):
    if not setCredential() :
        print ("Credential Defined")
    else:
        print ("Abort, cannot authenticate cloud credential")
        sys.exit ("Cannot authenticate")
        
    labelResponse = lc.labelAnalyze(img)
    #print(labelResponse)
    for entity in labelResponse ["responses"]:
    	for sub_entity in entity ["labelAnnotations"]:
    		#ToDO use syntax analyis here to deterine which keywords are events
    		# accept enitity with score above 70
    		if (dc.priorityEventInclusion(sub_entity['description'])==1):
    			event[sub_entity['description']]=sub_entity["score"]
    print(event)

    locationResponse=pc.locationAnalyze(img)
    for entity in locationResponse["responses"]:
    	for sub_entity in entity["landmarkAnnotations"]:
    		# if description is not empty then
    		if 'description' in sub_entity:
    			location[sub_entity['description']]=sub_entity['score']
    		break
    print(location)	
    			
    #return event
    							

if __name__ == '__main__':
     process_input("demo_pic/lm3.jpg")
     #print(oc.logoAnalyze("demo_pic/logo.jpg"))
     #print(oc.ocrAnalyze("demo_pic/ocr.jpg"))
     #print(pc.locationAnalyze("demo_pic/lm3.jpg"))
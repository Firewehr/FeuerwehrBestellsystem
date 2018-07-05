#!/usr/bin/env python2
import json
import locale
import os
import requests
import sys
import time
import datetime
import os.path

save_path = "C:\\POS-Daten"
completeName = os.path.join(save_path, "printPOS.txt")
url = 'http://ff-pos.square7.ch/print.php?type=1'

def EpsonFinishCut(total):
	myfile.write("!")#Set Double Width
	myfile.write("Total: EUR " + str(format(total, ',.2f')) + "\n")
	myfile.write("\x1B!\x00")#Set Normal
	myfile.write("\x1B\x61\x01")#set alignment center
	myfile.write("\nwww.ff-XXXXXXXXX.at\n")
	myfile.write("\n\n\n\n\n\n\n\x0D\x0c")#abstand
	myfile.write("\x1D\x56\x00\x0A")#schneiden
	myfile.close() 
	os.system("C:\POS-Daten\printi.bat")




while 1:
	try:
		myfile=open(completeName, "w+")
		print("Daten abrufen " + str(datetime.datetime.now().time()))
		data = ""
		response = requests.get(url, data=data)
		response_text = response.text
		json_object = response.json()
		tischnummer = '0'
		anzahlZeilen = len(json_object)
		j = 0
		total = 0
		for i in json_object:
			j = j + 1
			
			if i['tischnummer'] == tischnummer:
				print(i['cnt'] + "x " + i['Kurzbezeichnung'] + " a " + i['betrag'])
				total = total + (float(i['betrag']) * float(i['cnt']))
				myfile.write(i['cnt'] + "x " + i['Kurzbezeichnung'] + "\x09\x1B\x61\x01" + str(format(float(i['betrag']) * float(i['cnt']), ',.2f')) +  "\x1B!\x00\n")
				
				if j >= anzahlZeilen:
					EpsonFinishCut(total)
			else:
				print("\n\n************\nNeuer Tisch")
				#Epson.text("Neuer Tisch" + i['tischname'])
				print(i['tischname'])
				tischnummer = i['tischnummer']
				print(i['kellnerZahlung'])
				print(i['cnt'] + "x " + i['Kurzbezeichnung'] + " a " + i['betrag'])
				total = total + (float(i['betrag']) * float(i['cnt']))
				myfile.write("\x1B\x61\x01")#set alignment center
				myfile.write("!")#Set Double Width
				myfile.write("Freiwillige Feuerwehr\n")
				myfile.write("XXXXXXXX\n")
				myfile.write("\x1B!\x00")#Set Normal
				myfile.write("   Tisch:  ")
				myfile.write("!3"+ i['tischname'] + "\n")
				myfile.write("\x1B!\x00")#Set Normal
				myfile.write("\x1B\x61\x00")#set alignment left
				myfile.write("Kellner:  " + i['kellner'] + "\nBestellt: " + i['zeitstempel'] + "\n");#KellnerName
				myfile.write("          " + i['zeitKueche'] + "\n")
				myfile.write("\x1B\x44\x03\x19\x00")#set horizontal tabs myfile.write("\x1B\x44\x02\x03\x19\x00")#set horizontal tabs
				myfile.write(i['cnt'] + "x " + i['Kurzbezeichnung'] + "\x09\x1B\x61\x01" + str(format(float(i['betrag']) * float(i['cnt']), ',.2f')) +  "\x1B!\x00\n")
				
				if anzahlZeilen == 1:
					EpsonFinishCut(total)

		time.sleep(2)

	except:
		e = sys.exc_info()[0]
		print("Fehler", e)
		print(sys.exc_info())
		time.sleep(20)
#!/usr/bin/env python2
#encoding: UTF-8
#sudo apt-get install python-pip
#sudo pip install requests
#pip install python-escpos
import json
import locale
import os
import requests
import sys
import time
import datetime
#reload(sys)
#sys.setdefaultencoding('utf-8')
import escpos.printer
Epson = escpos.printer.Serial("/dev/ttyUSB0") #Adapt Printer and Device

def EpsonFinishCut(total):
	Epson.set(height=2,width=2,align='right')
	#myfile.write("Total: EUR " + str(format(total, ',.2f')) + "\n")#schneiden
	Epson.text("Total: EUR " + str(format(total, ',.2f')) + "\n")
	Epson.set(height=1,align='center')
	#Epson.text("\your website ...\n")
	#Epson.qr("") #Print QR Code
	Epson.cut()

url = 'url to pos system.... /print.php?type=1' #type=1 => Speisen, type=2 => Getränke


while 1:
    try:
		print("Daten abrufen " + str(datetime.datetime.now().time()))
		Epson.set(height=1,align='left')
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
				#with open("printPOS.txt", "a") as myfile:
				#myfile.write(i['cnt'] + "x" + i['Kurzbezeichnung'] + "\x09\x1B\x61\x01" + str(format(float(i['betrag']) * float(i['cnt']), ',.2f'))  + "\x1B!\x00\n")
				Epson.text(i['cnt'] + "x" + i['Kurzbezeichnung'] + "\x09\x1B\x61\x01" + str(format(float(i['betrag']) * float(i['cnt']), ',.2f')) +  "\x1B!\x00\n")

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
				Epson.set(height=2,align='center',width=2)
				Epson.text("Überschrift\n")
				Epson.set(height=1,align='center',width=1)
				Epson.text("Tisch ")
				Epson.set(height=4,align='center',width=4)
				Epson.text(i['tischname'] + "\n")
				Epson.set(height=1,align='left')
				Epson.text("Kellner: " + i['kellner'] + "\nBestellt: " + i['zeitstempel'] + "\n");#KellnerName
				Epson.text("          " + i['zeitKueche'] + "\n")
				Epson.text("\n")
				Epson.text("\x1B\x44\x03\x19\x00")#set horizontal tabs myfile.write("\x1B\x44\x02\x03\x19\x00")#set horizontal tabs
				Epson.text(i['cnt'] + "x" + i['Kurzbezeichnung'] + "\x09\x1B\x61\x01" + str(format(float(i['betrag']) * float(i['cnt']), ',.2f')) +  "\x1B!\x00\n")
				if anzahlZeilen == 1:
					EpsonFinishCut(total)
	
		time.sleep(2)
    
    except:
        e = sys.exc_info()[0]
        print("Fehler", e)
        print(sys.exc_info())
	time.sleep(20)

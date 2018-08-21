#!/usr/bin/env python2
#encoding: UTF-8
#sudo apt-get install python-pip
#sudo pip install requests
import json
import locale
import os
import requests
import sys
import time
import datetime
#reload(sys)
#sys.setdefaultencoding('utf-8')


#locale.setlocale( locale.LC_ALL, '' )

#from escpos import *

""" Seiko Epson Corp. Receipt Printer M129 Definitions (EPSON TM-T88IV) """
#Epson = escpos.Escpos(0x067b,0x2305,0)
#Epson = printer.Serial("/dev/usb/lp0")
#067b:2305
#Epson = printer.Usb(0x067b,0x2305)

#Bus 001 Device 007: ID 067b:2305 Prolific Technology, Inc. PL2305 Parallel Port
#Digitus Convertervid 0x067B pid 0x2305
#Epson = printer.Usb(0x067b,0x2305)

#Epson.text("Hello World\n")

#Epson.cut()



def EpsonFinishCut(total):
    #global globvar
    with open("printPOS.txt", "a") as myfile:
        #globvar = globvar + "\x1B!\x20"
        myfile.write("\x1B!\x20")#Set Double Width
		
        myfile.write("Total: EUR " + str(format(total, ',.2f')) + "\n")#schneiden
        myfile.write("\x1B!\x00")#Set Normal
        myfile.write("\x1B\x61\x01")#set alignment center
        myfile.write("\nwww.ff-.at\n")
        myfile.write("\n\n\n\n\n\n\n\x0D\x0c")#schneiden
        myfile.write("\x1D\x56\x00\x0A")#schneiden
    os.system('cat printPOS.txt > /dev/usb/lp3')
    print("schneiden")
    #print(globvar)

url = 'URL/print.php?type=2' #type=1 => Speisen, type=2 => Getränke

#Epson.set(align=u'left', font=u'a', text_type=u'normal', width=1, height=1, density=9, invert=False, smooth=False, flip=False)

while 1:
    try:
        print("Daten abrufen " + str(datetime.datetime.now().time()))
        data = ""
        response = requests.get(url, data=data)

        response_text = response.text

        json_object = response.json()

        tischnummer = '0'
		
        anzahlZeilen = len(json_object)
        j = 0
		
        #print("anzahl der zeilen", len(json_object))
        total = 0
        for i in json_object:
            j = j + 1
			
            if i['tischnummer'] == tischnummer:
				
                #print(i['cnt'] + "x " + i['Positionsname'])
                print(i['cnt'] + "x " + i['Kurzbezeichnung'] + " a " + i['betrag'])
                total = total + (float(i['betrag']) * float(i['cnt']))
				
                with open("printPOS.txt", "a") as myfile:
                    #myfile.write(i['cnt'] + "x" + i['Kurzbezeichnung'] + "\x09\x1B\x61\x01" + str(format(float(i['betrag']) * float(i['cnt']), ',.2f'))  + "\x1B!\x00\n")
                    myfile.write(i['cnt'] + "x" + i['Kurzbezeichnung'] + "\x09\x1B\x61\x01" + "\x1B!\x00\n")
					
                if j >= anzahlZeilen:
                    EpsonFinishCut(total)
                    #Epson.cut()
                    #os.system("printf '\\x1D\\x56\\x00\\x0A' >/dev/usb/lp0") #Schneiden
					
                    #with open("printPOS.txt", "a") as myfile:
                    #	myfile.write(i['kellnerZahlung'] + "\n")
					
            else:
                os.system("sudo rm printPOS.txt") #Schneiden
                print("\n\n************\nNeuer Tisch")
                #Epson.text("Neuer Tisch" + i['tischname'])
                print(i['tischname'])
                tischnummer = i['tischnummer']
				
                print(i['kellnerZahlung'])
				
                print(i['cnt'] + "x " + i['Kurzbezeichnung'] + " a " + i['betrag'])
                total = total + (float(i['betrag']) * float(i['cnt']))
				
                with open("printPOS.txt", "a") as myfile:
                    myfile.write("\x1B\x52\x02")#set character set Germany
					
                    myfile.write("\x1B\x61\x01")#set alignment center
					
                    myfile.write("\x1B!\x10")#Set Double Height
                    myfile.write("\x1B!\x20")#Set Double Width
                    myfile.write("FF-Manhartsbrunn\n")
                    myfile.write("Tisch " + i['tischname'] + "\n")
					
					
                    myfile.write("\x1B!\x00")#Set Normal
                    myfile.write("\x1B\x61\x00")#set alignment left
                    myfile.write("Kellner: " + i['kellner'] + "\nBestellt: " + i['zeitstempel'] + "\n");#KellnerName
					
					
                    myfile.write("          " + i['zeitKueche'] + "\n")
                    myfile.write("\x1B\x44\x03\x19\x00")#set horizontal tabs myfile.write("\x1B\x44\x02\x03\x19\x00")#set horizontal tabs
                    #myfile.write(i['cnt'] + "x" + i['Kurzbezeichnung'] + "\x09\x1B\x61\x01" + str(format(float(i['betrag']) * float(i['cnt']), ',.2f'))  + "\x1B!\x00\n")
                    myfile.write(i['cnt'] + "x" + i['Kurzbezeichnung'] + "\x09\x1B\x61\x01" + "\x1B!\x00\n")
				
                if anzahlZeilen == 1:
                    EpsonFinishCut(total)

            #str(text).encode('utf-8')
        time.sleep(2)
    except:
        e = sys.exc_info()[0]
        print("Fehler", e)
        print(sys.exc_info())
	time.sleep(20)

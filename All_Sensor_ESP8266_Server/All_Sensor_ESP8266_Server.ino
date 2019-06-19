#include <Arduino.h>
#include <ESP8266WiFi.h>
#include <ESP8266WiFiMulti.h>
#include <ESP8266HTTPClient.h>
#include <Wire.h>
#include <SDL_ESP8266_HR_AM2315.h>
#include <NDIRZ16.h>
#include <SoftwareSerial.h>

ESP8266WiFiMulti WiFiMulti;

/*const char* ssid = "BrainwaveEshop";
const char* pass = "0837684321";*/


const char* ssid = "RJINNO_2.4G";
const char* pass = "28322832";

/*const char* ssid = "vivo 1806";
const char* pass = "ployyyyy";*/

#if 1
__asm volatile ("nop");
#endif

#pragma GCC diagnostic ignored "-Wwrite-strings"

extern "C" {
#include "user_interface.h"
}

#define O2PIN A0
#define ledRO2 D6
#define ledRCO2 D5
#define ledGCO2 3
#define ledRT D4
#define ledGT D0
#define ledRH D3
#define ledGH 10

SDL_ESP8266_HR_AM2315 am2315;
SoftwareSerial mySerial(D7,D8);
NDIRZ16 mySensor = NDIRZ16(&mySerial);

float dataAM2315[2];  //Array to hold data returned by sensor.  [0,1] => [Humidity, Temperature]
boolean myOK;  // 1=successful read
int dataO2 = 0;
int ppmCO2;
float CO2;
float O2;
float Humidity;
float Temp;

void setup(){
 Serial.begin(115200); // sets the serial port to 9600
 mySerial.begin(9600);
 pinMode(O2PIN,INPUT);
 pinMode(ledRO2,OUTPUT);
 pinMode(ledRCO2,OUTPUT);
 pinMode(ledGCO2,OUTPUT); 
 pinMode(ledRT,OUTPUT);
 pinMode(ledGT,OUTPUT);
 pinMode(ledRH,OUTPUT);
 pinMode(ledGH,OUTPUT);
 digitalWrite(ledRO2,HIGH);
 digitalWrite(ledRCO2,HIGH);
 digitalWrite(ledRT,HIGH);
 digitalWrite(ledRH,HIGH);
 digitalWrite(ledGCO2,HIGH);
 digitalWrite(ledGT,HIGH);
 digitalWrite(ledGH,HIGH);
 Serial.println("ALL Test!");
  
 Serial.println("Wait 10 seconds for the sensor to starup");
 delay(10000);
 
 for (uint8_t t = 4; t > 0; t--) {
    Serial.printf("[SETUP] WAIT %d...\n", t);
    Serial.flush();
    delay(1000);
  }
  WiFiMulti.addAP(ssid,pass); // ssid , password
}
 
void loop(){
  myOK = am2315.readData(dataAM2315);
  Humidity = dataAM2315[0];
  Temp = dataAM2315[1];
  dataO2 = analogRead(O2PIN);
  O2 = 0.003*dataO2-0.0199;
  O2 = 48.825*O2+9.2506;
  if (O2 >= 35){
    O2 = -0.0042*O2*O2 + 1.201*O2 - 2.0925;
  }
  //Display
  Serial.print("%O2 : ");
  Serial.println(O2);
  Serial.print("Hum: "); Serial.println(Humidity);
  Serial.print("Temp: "); Serial.println(Temp);
  
  if (mySensor.measure()) 
  {
    Serial.print("CO2 Concentration is ");
    ppmCO2 = mySensor.ppm;
    CO2 = ppmCO2;//*0.0001; 
    Serial.print(CO2);
    Serial.println("ppm");
  }
  Serial.println("");

  //alarm
  if(O2 < 19.5 || O2 > 23.5){digitalWrite(ledRO2,LOW);}
  else{digitalWrite(ledRO2,HIGH);}

  
 //PPM
  if(CO2 > 1500){
    digitalWrite(ledRCO2,LOW);
    digitalWrite(ledGCO2,HIGH);
  }
  else if(CO2 > 1000 && CO2 <= 1500){
    digitalWrite(ledRCO2,LOW);
    digitalWrite(ledGCO2,LOW);
  }
  else{
    digitalWrite(ledRCO2,HIGH);
    digitalWrite(ledGCO2,LOW);
  }
/*
  // % Volume
  if(CO2 > 0.15){
    digitalWrite(ledRCO2,LOW);
    digitalWrite(ledGCO2,HIGH);
  }
  else if(CO2 > 0.1 && CO2 <= 0.15){
    digitalWrite(ledRCO2,LOW);
    digitalWrite(ledGCO2,LOW);
  }
  else{
    digitalWrite(ledRCO2,HIGH);
    digitalWrite(ledGCO2,LOW);
  }*/
  
  if(Temp > 27 || Temp < 17){
    digitalWrite(ledRT,LOW);
    digitalWrite(ledGT,HIGH);
  }
  else if((Temp>24 && Temp<=27)||(Temp<20 && Temp>=17)){
    digitalWrite(ledRT,LOW);
    digitalWrite(ledGT,LOW);
  }
  else{
    digitalWrite(ledRT,HIGH);
    digitalWrite(ledGT,LOW);
    
  }
  
  if(Humidity<20||Humidity>70){
    digitalWrite(ledRH,LOW);
    digitalWrite(ledGH,HIGH);
  }
  else if((Humidity<30&&Humidity>=20)||(Humidity>60&&Humidity<=70)){
    digitalWrite(ledRH,LOW);
    digitalWrite(ledGH,LOW);
  }
  else{
    digitalWrite(ledRH,HIGH);
    digitalWrite(ledGH,LOW);
  }

  //server mysql
  if((WiFiMulti.run() == WL_CONNECTED)){
    HTTPClient http;
    String url = "http://medgas.000webhostapp.com/insert.php?Temp="
    +String(Temp)+"&Humid="+String(Humidity)+"&O2="+String(O2)+"&CO2="+String(CO2);
    Serial.println(url);
    http.begin(url);

    int httpCode = http.GET();
    if (httpCode > 0) {
      Serial.printf("[HTTP] GET... code: %d\n", httpCode);
      if (httpCode == HTTP_CODE_OK) {
        String payload = http.getString();
        Serial.println(payload);
      }
    } else {
      Serial.printf("[HTTP] GET... failed, error: %s\n", http.errorToString(httpCode).c_str());
    }
    http.end();
  }
  
  delay(30000);
}// wait 100ms for next readin

#include <Arduino.h>
#include <SensirionI2cScd30.h>
#include <Wire.h>

//----Activation des messages de debug-----
bool activationSerialPrint = false;
//-----------------------------------------

// Definition du capteur
SensirionI2cScd30 sensor;

// Definition des variables pour les messages d'erreurs du scd30
static char errorMessage[128];
static int16_t error;

// Definition des broches led
#define PIN_RED    25 // GPIO25
#define PIN_GREEN  26 // GPIO26
#define PIN_BLUE   27 // GPIO27

void setup() {

  // Definition des modes de la led
  pinMode(PIN_RED,   OUTPUT);
  pinMode(PIN_GREEN, OUTPUT);
  pinMode(PIN_BLUE,  OUTPUT);

  // Initialisation de la communication série
  Serial.begin(115200);
  Serial2.begin(9600);
  if (activationSerialPrint)Serial.println("Initalisation SCD30...");
  Wire.begin(); // initalisation de la lisaison I2C
  sensor.begin(Wire, SCD30_I2C_ADDR_61); // Création de l'objet du scd30
  sensor.softReset(); // Remise à 0 du capteur

  // Initialisation du capteur SCD30
  uint8_t major = 0;
  uint8_t minor = 0;
  error = sensor.readFirmwareVersion(major, minor);
  if (error != NO_ERROR) {
    Serial.print("Error trying to execute readFirmwareVersion(): ");
    errorToString(error, errorMessage, sizeof errorMessage);
    Serial.println(errorMessage);
    return;
  }

  // Debut des mesures
  sensor.startPeriodicMeasurement(0);

  // Configuration LoRaWAN
  if (activationSerialPrint)Serial.println("Setting up LoRaWAN...");
  Serial2.println("AT+MODE=LWOTAA");
  delay(1000);
  Serial2.println("AT+DR=EU868");
  delay(1000);
  Serial2.println("AT+CH=NUM,0-2");
  delay(1000);
  Serial2.println("AT+KEY=APPKEY,2B7E151628AED2A6ABF7158809CF4F3C");
  delay(1000);

  /*Serial2.println("AT+KEY=NWKSKEY,2B7E151628AED2A6ABF7158809CF4F3C");
  delay(1000);
  Serial2.println("AT+KEY=APPSKEY,2B7E151628AED2A6ABF7158809CF4F3C");
  delay(1000);*/

  Serial2.println("AT+PORT=8");
  delay(1000);

// ----------Demande d'appairage avec la passerelle----------------------------

  Serial2.println("AT+JOIN");
  delay(1000);

  while (!rechercheMessage("+JOIN: Network joined")) {
    for(int count = 0; count < 3 ; count++){
      delay(1000);
    }
    Serial2.println("AT+JOIN");
    if (activationSerialPrint)Serial.println("Reconnexion en cours ...");
  }

  // Le code ci-dessous sera exécuté une fois que le message attendu est reçu
  if (activationSerialPrint)Serial.println("Join reussi !");

// ----------------------------------------------------------------------------
  Serial2.println("AT+ID");
  delay(1000);
  if (activationSerialPrint)Serial.println("Setup Reussi !");
}

void loop() {
  if (activationSerialPrint)debug();
  // Initalisation des variables pour les mesures du capteur
    float co2 = 0.0;
    float temperature = 0.0;
    float sommetemperature = 0.0;
    float humidity = 0.0;

    // Debut des mesures periodiques
    sensor.startPeriodicMeasurement(0);

    // boucle de 15 messures espacé de 2s donc 30 secondes de mesures de temperatures afin d'avoir une moyenne
    for(int i = 0; i < 15; i++) {
        // lecture des données capteur
        error = sensor.blockingReadMeasurementData(co2, temperature, humidity);

        // En cas d'ereur
        if (error != NO_ERROR) {
            Serial.print("Erreur lors de la lecture de la température : ");
            errorToString(error, errorMessage, sizeof errorMessage);
            Serial.println(errorMessage);
            return;
        }

        // Sommes des temperature sur 30 secondes
        sommetemperature += temperature;

        // Affichage des temperatures sur 30 secondes
        if (activationSerialPrint)Serial.println(temperature);
        // Attendre 2s avant la prochaine mesure
        delay(1000);
        delay(1000);
    }



    // Calcul de la moyenne
    float moytemperature = sommetemperature / 15.0;

    // Convertion de co2 pour avoir un nombre entier
    int co2Int = static_cast<int>(co2);

    // Affichage Led du taux de Co2
    if (co2 < 800) {
      // LED vert
      analogWrite(PIN_RED, 0);
      analogWrite(PIN_GREEN, 255);
      analogWrite(PIN_BLUE, 0);
    } else if (co2 >= 800 && co2 <= 1200) {
        // LED bleu
      analogWrite(PIN_RED, 0);
      analogWrite(PIN_GREEN, 0); // Orange nécessite une combinaison de rouge et vert
      analogWrite(PIN_BLUE, 255);
    } else {
      // LED rouge
      analogWrite(PIN_RED, 255);
      analogWrite(PIN_GREEN, 0);
      analogWrite(PIN_BLUE, 0);
    }

    // Affichage des données pour debug
    if (activationSerialPrint){
      Serial.print("Humidity: ");
      Serial.print(humidity);
      Serial.print(" %, Temperature: ");
      Serial.print(moytemperature);
      Serial.print(" °C, CO2: ");
      Serial.print(co2Int);
      Serial.println(" ppm, ");

    // ----------------Envoyer les données via LoRaWAN----------------
    Serial.println("Sending data via LoRaWAN...");
    }

    // Convertir les valeurs en chaînes de caractères valide pour le decodage
    String message = "AT+MSG=\"" + String(humidity) + "_" + String(moytemperature) + "_" + String(co2Int) + "\"";

    // Envoyer le message via la communication série
    Serial2.println(message);
    delay(1000);
    // ---------------------------------------------------------------

    // Attente de la commande "+MSG: Done" signifiant la fin d'envoie
    while (!rechercheMessage("+MSG: Done")) {
      for(int count = 0; count < 3 ; count++){
        delay(1000);
      }
      if (activationSerialPrint)Serial.println("Accusé de reception en cours ...");
    }
    if (activationSerialPrint)Serial.println("Données bien receptionnées !");

    // Arret des mesures periodiques du capteur
    sensor.stopPeriodicMeasurement();

    // Mise en sommeil de l'ESP32 pour une consomation minimale
    if (activationSerialPrint)Serial.println("Je ronfle ... Zzz");
    delay(500);
    esp_sleep_enable_timer_wakeup(1000000*56*10);
    int ret = esp_light_sleep_start();
}

// Fonction permettant d'attendre un message reçu par la liaison serie...
bool rechercheMessage(String messageAttendu) {
  while (Serial2.available() > 0) {
    String ligne = Serial2.readStringUntil('\n');
    if (activationSerialPrint)Serial.println(ligne);  // Affiche la ligne reçue en cas de debug

    if (ligne.indexOf(messageAttendu) != -1) {
      return true; // Le message attendu a été trouvé
    }
  }
  return false; // Le message attendu n'a pas été trouvé
}

// Fonction permettant de d'afficher les message de la liaison serie2 dans la liaison serie 1
void debug() {
  while(Serial2.available()){
    char inByte = Serial2.read();
    Serial.print(inByte);
  }
}

// Fonction permettant de d'afficher les message de la liaison serie2 dans la liaison serie 1 et inversement
void debugg() {
  while(Serial2.available()){
    char inByte = Serial2.read();
    Serial.print(inByte);
  }
  while(Serial.available()){
    char inByte = Serial.read();
    Serial2.print(inByte);
  }
}

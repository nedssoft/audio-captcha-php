<?php

// includes the autoloader for libraries installed with composer
require __DIR__ . '/vendor/autoload.php';

// Imports the Cloud Client Library
use Google\Cloud\TextToSpeech\V1\AudioConfig;
use Google\Cloud\TextToSpeech\V1\AudioEncoding;
use Google\Cloud\TextToSpeech\V1\SsmlVoiceGender;
use Google\Cloud\TextToSpeech\V1\SynthesisInput;
use Google\Cloud\TextToSpeech\V1\TextToSpeechClient;
use Google\Cloud\TextToSpeech\V1\VoiceSelectionParams;

// instantiates a client
putenv("GOOGLE_APPLICATION_CREDENTIALS=" . __DIR__ . "/credentials.json");



// sets text to be synthesised
function textToSpeech($text)
{
    $client = new TextToSpeechClient();
    $synthesisInputText = (new SynthesisInput())
        ->setText($text);

    // build the voice request, select the language code ("en-US") and the ssml
    // voice gender
    $voice = (new VoiceSelectionParams())
        ->setLanguageCode('en-US')
        ->setSsmlGender(SsmlVoiceGender::FEMALE);

    // Effects profile
    $effectsProfileId = "telephony-class-application";

    // select the type of audio file you want returned
    $audioConfig = (new AudioConfig())
        ->setAudioEncoding(AudioEncoding::MP3)
        ->setEffectsProfileId(array($effectsProfileId));

    // perform text-to-speech request on the text input with selected voice
    // parameters and audio file type
    $response = $client->synthesizeSpeech($synthesisInputText, $voice, $audioConfig);
    $audioContent = $response->getAudioContent();

    // the response's audioContent is binary
    file_put_contents("audio.mp3", $audioContent);
}

class TextToSpeech
{

    public function __construct(string $text)
    {
        $this->text = $text;
    }


    public function __invoke()
    {
        
        $client = new TextToSpeechClient();
        $synthesisInputText = (new SynthesisInput())
            ->setText($this->text);

        // build the voice request, select the language code ("en-US") and the ssml
        // voice gender
        $voice = (new VoiceSelectionParams())
            ->setLanguageCode('en-US')
            ->setSsmlGender(SsmlVoiceGender::FEMALE);

        // Effects profile
        $effectsProfileId = "telephony-class-application";

        // select the type of audio file you want returned
        $audioConfig = (new AudioConfig())
            ->setAudioEncoding(AudioEncoding::MP3)
            ->setEffectsProfileId(array($effectsProfileId));

        // perform text-to-speech request on the text input with selected voice
        // parameters and audio file type
        $response = $client->synthesizeSpeech($synthesisInputText, $voice, $audioConfig);
        $audioContent = $response->getAudioContent();

        // the response's audioContent is binary
        file_put_contents("audio.mp3", $audioContent);
        
    }
}

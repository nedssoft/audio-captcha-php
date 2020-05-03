## Captcha Generator

This project generates Captcha and displays in both text and audio formats.
The audio enables users with visual impairment to access the feature

## Usage

### Prerequisites
- Create a Google cloud account
- Generate the API key Json and save in /credentials.json
- Follow this [link](https://cloud.google.com/text-to-speech/docs/quickstart-client-libraries#client-libraries-usage-php) to enable the API
- PHP 7+ installed
- [composer](https://getcomposer.org/download/) installed


## Installation
- git clone https://github.com/nedssoft/audio-captcha-php.git 
- cd audio-captcha-php
- cp .env.example .env
- Fill the Keys in the .env accordingly with the API keys generated above
- composer install
- php -S localhost:8000

## usage
- Once you have completed the installation steps above
- Visit http://localhost:8000 on the browser to test the captcha

## Core Features
- Captcha input is sanitized to strip strip accents
- Should be able to validate captcha input coming from different languages i.e Chinese, Greece, French etc
- Generates captcha image in jpg with minimal quality to increase latency
- Validates the integrity of captcha requests using CSRF protection
- Generate voice output from text
- Text being rendered into either image or audio is a maximum of 3 words and 60 characters
- Captcha confirmation

## Tools used
- [Google-Speech-to-Text  API](https://cloud.google.com/speech-to-text/)

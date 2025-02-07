<?php

declare(strict_types=1);

namespace Georgehadjisavva\ElevenLabsClient\TextToSpeech;

use Exception;
use Georgehadjisavva\ElevenLabsClient\ElevenLabsClient;
use Georgehadjisavva\ElevenLabsClient\Enums\LatencyOptimizationEnum;
use Georgehadjisavva\ElevenLabsClient\Enums\ModelsEnum;
use Georgehadjisavva\ElevenLabsClient\Enums\VoicesEnum;
use Georgehadjisavva\ElevenLabsClient\Enums\VoiceSettingsEnum;
use Georgehadjisavva\ElevenLabsClient\Responses\ErrorResponse;
use Georgehadjisavva\ElevenLabsClient\Responses\SuccessResponse;
use Georgehadjisavva\ElevenLabsClient\Traits\ExceptionHandlerTrait;

class TextToSpeech implements TextToSpeechInterface
{
    use ExceptionHandlerTrait;

    protected $client;

    public function __construct(ElevenLabsClient $client)
    {
        $this->client = $client->getHttpClient();
    }

     
   /**
     * Generate a voice based on the provided content.
     *
     * @param string  $content The content for voice generation.
     * @param string  $voice_id The ID of the voice to use (default: 21m00Tcm4TlvDq8ikWAM ).
     * @param bool    $optimize_latency Whether to optimize for latency (default: 0 ).
     * @param ?string $model_id of the model that will be used
     * @param ?array  $voice_settings : Voice settings overriding stored setttings for the given voice
     *
     * @return array status,message
     * 
     * See : https://docs.elevenlabs.io/api-reference/text-to-speech
     */
    public function generate(string $content, 
    string $voice_id = VoicesEnum::RACHEL, 
    ?bool $optimize_latency = LatencyOptimizationEnum::DEFAULT,
    ?string $model_id = ModelsEnum::ELEVEN_MONOLINGUAL_V1, 
    ?array $voice_settings = []) : array
    {
        try {

            $requestData = [
                    'text' => $content,
                    'model_id' => $model_id
            ];

            if(!empty($voice_settings)) {
          
                $diff = array_diff_key($voice_settings,array_flip(VoiceSettingsEnum::ALLOWED_VOICESETTINGS));
                
                //ensure the validity of voice_settings keys
                if($diff) {
                    return (new ErrorResponse(400,"You provided invalid voice settings"))->getResponse();
                }
                $requestData['voice_settings'] = $voice_settings;
            };

            $response = $this->client->post('text-to-speech/' . $voice_id, [
                'json' => $requestData,
            ]);

            $status = $response->getStatusCode();

            if ($status === 200) {
                return (new SuccessResponse($status, "Voice Succesfully Generated"))->getResponse();
            }
        } catch (Exception $e) {
            return $this->handleException($e);
          
        }
    }


   /**
     * Generate a voice based on the provided content.
     *
     * @param string  $content The content for voice generation.
     * @param string  $voice_id The ID of the voice to use (default: 21m00Tcm4TlvDq8ikWAM ).
     * @param bool    $optimize_latency Whether to optimize for latency (default: 0 ).
     * @param ?string $model_id of the model that will be used
     * @param ?array  $voice_settings : Voice settings overriding stored setttings for the given voice
     *
     * @return string|array audio file contents or error response with status and message
     *
     * See : https://docs.elevenlabs.io/api-reference/text-to-speech
     */
    public function generate_download(string $content,
    string $voice_id = VoicesEnum::RACHEL,
    ?bool $optimize_latency = LatencyOptimizationEnum::DEFAULT,
    ?string $model_id = ModelsEnum::ELEVEN_MONOLINGUAL_V1,
    ?array $voice_settings = []) : string|array
    {
        try {

            $requestData = [
                    'text' => $content,
                    'model_id' => $model_id
            ];

            if(!empty($voice_settings)) {

                $diff = array_diff_key($voice_settings,array_flip(VoiceSettingsEnum::ALLOWED_VOICESETTINGS));

                //ensure the validity of voice_settings keys
                if($diff) {
                    return (new ErrorResponse(400,"You provided invalid voice settings"))->getResponse();
                }
                $requestData['voice_settings'] = $voice_settings;
            };

            $response = $this->client->post('text-to-speech/' . $voice_id, [
                'json' => $requestData,
            ]);

            $status = $response->getStatusCode();

            if ($status === 200) {
                return $response->getBody()->getContents();
            }
        } catch (Exception $e) {
            return $this->handleException($e);

        }
    }

     /**
     * Converts text into speech using a voice of your choice and returns audio as an audio stream.
     *
     * @param string  $content The content for voice generation.
     * @param string  $voice_id The ID of the voice to use (default: 21m00Tcm4TlvDq8ikWAM ).
     * @param bool    $optimize_latency Whether to optimize for latency (default: 0 ).
     * @param ?string $model_id of the model that will be used
     * @param ?array  $voice_settings : Voice settings overriding stored setttings for the given voice
     *
     * @return array status,message
     * 
     * See : https://docs.elevenlabs.io/api-reference/text-to-speech
     */
    public function generate_stream(string $content, 
    string $voice_id = VoicesEnum::RACHEL, 
    ?bool $optimize_latency = LatencyOptimizationEnum::DEFAULT,
    ?string $model_id = ModelsEnum::ELEVEN_MONOLINGUAL_V1, 
    ?array $voice_settings = []) : array
    {
        try {

            $requestData = [
                    'text' => $content,
                    'model_id' => $model_id
            ];

            if(!empty($voice_settings)) {
                $diff = array_diff_key($voice_settings,array_flip(VoiceSettingsEnum::ALLOWED_VOICESETTINGS));

                //ensure the validity of voice_settings keys
                if($diff) {
                    return (new ErrorResponse(400,"You provided invalid voice settings"))->getResponse();
                }
                $requestData['voice_settings'] = $voice_settings;
            };

            $response = $this->client->post('text-to-speech/' . $voice_id. '/stream', [
                'json' => $requestData,
            ]);

            $status = $response->getStatusCode();

            if ($status === 200) {
                return (new SuccessResponse($status, "Voice Succesfully Generated"))->getResponse();
            }
        } catch (Exception $e) {
            return $this->handleException($e);
          
        }
    }

    
}



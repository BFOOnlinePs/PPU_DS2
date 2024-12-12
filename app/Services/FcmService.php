<?php

namespace App\Services;

use App\Enums\NotificationTypeEnum;
use App\Models\FCMRegistrationTokens;
use App\Models\NotificationModel;
use App\Models\NotificationUserModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Firebase\Exception\Messaging\NotFound;
use Kreait\Firebase\Exception\Messaging\InvalidArgument;


class FcmService
{
    protected $messaging;

    public function __construct()
    {
        $firebase = (new Factory)->withServiceAccount(config('firebase.projects.app.credentials'));
        $this->messaging = $firebase->createMessaging();
    }


    protected function determineNotificationDetails(
        NotificationTypeEnum $type,
        // int $targetId = null,
        // int $relatedId = null
    ): array {
        switch ($type) {
            case NotificationTypeEnum::MESSAGE:
                return [
                    'title' => trans('messages.notification_send_message_title'),
                    'body' => Auth::user()->name . ' ' . trans('messages.notification_send_message_body'),
                    'targetType' => 'conversation',
                    'relatedType' => 'message',
                    'targetUrl' => null,
                ];
            case NotificationTypeEnum::PAYMENT:
                return [
                    'title' => trans('messages.notification_add_payment_title'),
                    'body' => Auth::user()->name . ' ' . trans('messages.notification_add_payment_body'),
                    'targetType' => 'payment',
                    'relatedType' => null,
                    'targetUrl' => null,
                ];
            default:
                return [
                    'title' => 'Default Title',
                    'body' => 'Default Body',
                    'targetType' => null,
                    'relatedType' => null,
                    'targetUrl' => null,
                ];
        }
    }

    /// handle the payload
    // public function sendNotification(String $title, String $body, array $userIds, String $targetType = null, int $targetId = null, String $relatedType = null, $relatedId = null)
    public function sendNotification(
        NotificationTypeEnum $type,
        array $userIds,
        int $targetId = null,
        int $relatedId = null
    ) {
        // Determine the notification details based on the type
        $details = $this->determineNotificationDetails($type);

        // // remove the current user from the list
        // $userIds = array_filter($userIds, fn($id) => $id != Auth::user()->u_id);

        Log::info('aseel in sendNotification');
        Log::info('aseel userIds', $userIds);

        $tokens = FCMRegistrationTokens::whereIn('frt_user_id', $userIds)
            ->pluck('frt_registration_token')
            ->toArray();

        Log::info('aseel tokensByUser', $tokens);

        $notificationModel = NotificationModel::create([
            'title' => $details['title'],
            'body' =>  $details['body'],
            'target_type' => $details['targetType'],
            'target_id' => $targetId,
            'related_type' => $details['relatedType'],
            'related_id' => $relatedId,
            'target_url' => $details['targetUrl'],

        ]);

        foreach ($userIds as $userId) {
            NotificationUserModel::create([
                'notification_id' => $notificationModel->id,
                'user_id' => $userId,
            ]);
        }

        Log::info("aseel 2 models created");

        // $this->sendToTokens($title, $body, $tokensByUser, $targetType, $targetId, $relatedType, $relatedId);
        $notification = Notification::create(
            $details['title'],
            $details['body'],
        );

        $message = CloudMessage::new()
            ->withNotification($notification);

        $data = [];

        if ($details['targetType'] !== null) {
            $data['targetType'] = $details['targetType'];
        }

        if ($targetId !== null) {
            $data['targetId'] = $targetId;
        }

        if ($details['relatedType'] !== null) {
            $data['relatedType'] = $details['relatedType'];
        }

        if ($relatedId !== null) {
            $data['relatedId'] = $relatedId;
        }

        // Attach data payload if it exists
        if (!empty($data)) {
            $message = $message->withData($data);
        }

        if (!empty($tokens)) {
            try {
                // Send the message via FCM
                $report = $this->messaging->sendMulticast($message, $tokens);

                Log::info('Message sent successfully to tokens:', ['tokens' => $tokens]);
                Log::info('Success count:', ['count' => $report->successes()->count()]);
                Log::info('Failure count:', ['count' => $report->failures()->count()]);
                Log::info('unknownTokens', ['tokens' => $report->unknownTokens()]);
                Log::info('invalidTokens', ['tokens' => $report->invalidTokens()]);

                // Handle unknown tokens
                foreach ($report->unknownTokens() as $token) {
                    FCMRegistrationTokens::where('frt_registration_token', $token)->delete();
                    Log::info('Deleted unknown FCM token:', ['token' => $token]);
                }

                // Handle invalid tokens
                foreach ($report->invalidTokens() as $token) {
                    FCMRegistrationTokens::where('frt_registration_token', $token)->delete();
                    Log::info('Deleted invalid FCM token:', ['token' => $token]);
                }
            } catch (NotFound $e) {
                Log::info('Token not found: ' . $e->getMessage());
            } catch (InvalidArgument $e) {
                Log::info('Invalid argument: ' . $e->getMessage());
            } catch (\Exception $e) {
                Log::info('Error sending message: ' . $e->getMessage());
            }
        } else {
            Log::info('No tokens found');
        }
    }

    // private function sendToTokens($title, $body, $tokens, String $targetType = null, int $targetId = null, String $relatedType = null, $relatedId = null)
    // {
    //     $notification = Notification::create($title, $body);

    //     $message = CloudMessage::new()
    //         ->withNotification($notification);

    //     if (!empty($tokens)) {
    //         try {
    //             // Send the message via FCM
    //             $report = $this->messaging->sendMulticast($message, $tokens);

    //             Log::info('Message sent successfully to tokens:', ['tokens' => $tokens]);
    //             Log::info('Success count:', ['count' => $report->successes()->count()]);
    //             Log::info('Failure count:', ['count' => $report->failures()->count()]);
    //             Log::info('unknownTokens', ['tokens' => $report->unknownTokens()]);
    //             Log::info('invalidTokens', ['tokens' => $report->invalidTokens()]);

    //             // Handle unknown tokens
    //             foreach ($report->unknownTokens() as $token) {
    //                 FCMRegistrationTokens::where('frt_registration_token', $token)->delete();
    //                 Log::info('Deleted unknown FCM token:', ['token' => $token]);
    //             }

    //             // Handle invalid tokens
    //             foreach ($report->invalidTokens() as $token) {
    //                 FCMRegistrationTokens::where('frt_registration_token', $token)->delete();
    //                 Log::info('Deleted invalid FCM token:', ['token' => $token]);
    //             }
    //         } catch (NotFound $e) {
    //             Log::info('Token not found: ' . $e->getMessage());
    //         } catch (InvalidArgument $e) {
    //             Log::info('Invalid argument: ' . $e->getMessage());
    //         } catch (\Exception $e) {
    //             Log::info('Error sending message: ' . $e->getMessage());
    //         }
    //     } else {
    //         Log::info('No tokens found');
    //     }
    // }


    // // not used anymore
    // private function sendToToken($title, $body, $userId, $token, $type, $type_id)
    // {
    //     $notification = Notification::create($title, $body);

    //     // Create a message targeting a specific token
    //     // i edited it since toTarget is deprecated
    //     $message = CloudMessage::new()
    //         ->toToken($token)
    //         ->withNotification($notification);

    //     $data = [];

    //     if ($type !== null) {
    //         $data[config('constants.notification.type')] = $type;
    //     }

    //     if ($type_id !== null) {
    //         $data[config('constants.notification.type_id')] = $type_id;
    //     }

    //     // Attach data payload if it exists
    //     if (!empty($data)) {
    //         $message = $message->withData($data);
    //     }

    //     try {
    //         // Send the message via FCM
    //         $this->messaging->send($message);
    //         Log::info('Message sent successfully to token: ' . $token);
    //     } catch (NotFound $e) {
    //         Log::info('Token not found: ' . $e->getMessage());
    //         $fcmUserToken = FCMRegistrationTokens::where('frt_user_id', $userId)
    //             ->where('frt_registration_token', $token)->get();

    //         // if by accident saved the same token more than one time
    //         foreach ($fcmUserToken as $token) {
    //             Log::info('delete token: ' . $token);

    //             $token->delete();
    //         }
    //     } catch (InvalidArgument $e) {
    //         Log::info('Invalid argument: ' . $e->getMessage());
    //     } catch (\Exception $e) {
    //         Log::info('Error sending message: ' . $e->getMessage());
    //     }
    // }
}

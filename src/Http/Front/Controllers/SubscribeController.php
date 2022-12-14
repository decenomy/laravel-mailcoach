<?php

namespace Spatie\Mailcoach\Http\Front\Controllers;

use Spatie\Mailcoach\Enums\SubscriptionStatus;
use Spatie\Mailcoach\Http\Front\Requests\CreateSubscriptionRequest;
use Spatie\Mailcoach\Models\Subscriber;
use Spatie\Mailcoach\Traits\UsesMailcoachModels;
use Symfony\Component\HttpFoundation\Response;

class SubscribeController
{
    use UsesMailcoachModels;

    public function __invoke(CreateSubscriptionRequest $request)
    {
        if (! $emailList = $request->emailList()) {
            abort(404, 'Could not find the list');
        }

        if ($emailList->getSubscriptionStatus($request->email) === SubscriptionStatus::SUBSCRIBED) {
            $subscriber = $this->getSubscriberClass()::findForEmail($request->email, $emailList);
            $subscriber->addTags($request->tags());

            return $this->getAlreadySubscribedResponse($request);
        }

        $subscriber = $this->getSubscriberClass()::createWithEmail($request->email)
            ->withAttributes($request->subscriberAttributes())
            ->redirectAfterSubscribed($request->redirect_after_subscribed ?? '')
            ->syncTags($request->tags())
            ->subscribeTo($emailList);

        return $subscriber->isUnconfirmed()
            ? $this->getSubscriptionPendingResponse($request, $subscriber)
            : $this->getSubscribedResponse($request, $subscriber);
    }

    protected function getSubscriptionPendingResponse(CreateSubscriptionRequest $request, Subscriber $subscriber): Response
    {
        if ($urlFromRequest = $request->redirect_after_subscription_pending) {
            return redirect()->to($request->redirect_after_subscription_pending);
        }

        if ($urlFromEmailList = $request->emailList()->redirect_after_subscription_pending) {
            return redirect()->to($urlFromEmailList);
        }

        return response()->view('mailcoach::landingPages.confirmSubscription', compact('subscriber'));
    }

    protected function getSubscribedResponse(CreateSubscriptionRequest $request, Subscriber $subscriber): Response
    {
        if ($urlFromRequest = $request->redirect_after_subscribed) {
            return redirect()->to($request->redirect_after_subscribed);
        }

        if ($urlFromEmailList = $request->emailList()->redirect_after_subscribed) {
            return redirect()->to($urlFromEmailList);
        }

        return response()->view('mailcoach::landingPages.subscribed', compact('subscriber'));
    }

    public function getAlreadySubscribedResponse(CreateSubscriptionRequest $request): Response
    {
        if ($urlFromRequest = $request->redirect_after_already_subscribed) {
            return redirect()->to($urlFromRequest);
        }

        if ($urlFromEmailList = $request->emailList()->redirect_after_already_subscribed) {
            return redirect()->to($urlFromEmailList);
        }

        return response()->view('mailcoach::landingPages.alreadySubscribed');
    }
}

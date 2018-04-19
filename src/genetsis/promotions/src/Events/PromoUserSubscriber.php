<?php namespace Genetsis\Promotions\Events;

use Genetsis\Promotions\Contracts\PromoUserEmailInterface;
use Genetsis\Promotions\Contracts\PromoUserInterface;
use Genetsis\Promotions\Models\User;

class PromoUserSubscriber
{

    /**
     * Handle user login events.
     */
    public function onUserLogin($event) {
        echo 'Login';
        var_dump($event);
    }

    /**
     * Handle user logout events.
     */
    public function onUserCreated(PromoUserInterface $new_promo_user) {

        try {

            $promo_user = User::updateOrCreate([
                'id' => $new_promo_user->getId(),
                'name' => ($new_promo_user instanceof PromoUserEmailInterface) ? $new_promo_user->getName() : '',
                'email' => ($new_promo_user instanceof PromoUserEmailInterface) ? $new_promo_user->getEmail() : '',
                'sponsor_code' => hash('crc32',$new_promo_user->getId(), false)
            ]);

            \Log::info('Promo User Created - sponsor code: ' . $promo_user->sponsor_code);
        } catch (\Exception $e) {
            \Log::error('Exception: '.$e->getMessage());
        }
    }


    public function subscribe($events)
    {
        $events->listen(
            PromoUserCreated::class,
            PromoUserSubscriber::class.'@onUserLogin'
        );

        $events->listen('promouser.created', PromoUserSubscriber::class.'@onUserCreated');

    }

}

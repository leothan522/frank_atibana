<?php

namespace App\Livewire\FCM;

use App\Models\Fcm;
use App\Models\User;
use App\Services\FirebaseCloudMessagingService;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Kreait\Firebase\Exception\FirebaseException;
use Kreait\Firebase\Exception\MessagingException;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Livewire\Attributes\On;
use Livewire\Component;

class FcmComponent extends Component
{
    use LivewireAlert;

    public $title, $body, $withData = 0, $dispositivos = "todos", $keys = [], $values = [], $items = 0;

    public function render()
    {
        $users = Fcm::select('users_id')->groupBy('users_id')->get();
        return view('livewire.f-c-m.fcm-component')
            ->with('listarUsers', $users);
    }

    public function limpiar()
    {
        $this->reset(['title', 'body', 'withData', 'dispositivos', 'keys', 'values', 'items']);
        $this->resetErrorBag();
    }

    protected $rules = [
        'title' => 'required|min:4',
        'body' => 'required|min:4',
        'dispositivos' => 'required',
        'keys.*' => 'nullable|min:4|alpha_dash:ascii|required_if:withData,1',
        'values.*' => 'required_if:withData,1',
    ];

    public function sendMessage()
    {
        $this->validate();
        try {

            $tokens = [];

            $messaging = FirebaseCloudMessagingService::connect();

            $notificacion = Notification::fromArray([
                'title' => $this->title,
                'body' => $this->body,
                'image' => 'https://picsum.photos/400/200'
            ]);

            $data = [];
            for ($i = 0; $i < $this->items; $i++){
                $data[$this->keys[$i]] = $this->values[$i];
            }

            if ($this->dispositivos != "todos") {

                $user = User::where('rowquid', $this->dispositivos)->first();
                if ($user){
                    $id = $user->id;
                    $tokens = Fcm::where('users_id', $id)->get();
                }

            } else {
                $tokens = Fcm::all();
            }

            foreach ($tokens as $token){
                if (!$this->withData){
                    $message = CloudMessage::withTarget('token', $token->token)
                        ->withNotification($notificacion);
                }else{
                    $message = CloudMessage::withTarget('token', $token->token)
                        ->withNotification($notificacion)
                        ->withData($data);
                }
                $messaging->send($message);
            }

            $this->alert('success', 'Mensaje enviado.');

        } catch (MessagingException|FirebaseException $e) {
            $this->alert('warning', '¡ERROR FCM!', [
                'position' => 'center',
                'timer' => '',
                'toast' => false,
                'text' => $e->getMessage(),
                'showConfirmButton' => true,
                'onConfirmed' => '',
                'confirmButtonText' => 'OK',
            ]);
        }
    }

    #[On('tokenSeleccionado')]
    public function tokenSeleccionado($token)
    {
        $this->dispositivos = $token;
    }

    public function btnWithData($withData)
    {
        if (!$withData){
            $this->withData = 1;
            if (!$this->items){
                $this->items = 1;
                $this->keys[0] = null;
                $this->values[0] =null;
            }
        }else{
            $this->withData = 0;
        }
    }

    public function setItems($opcion)
    {
        if ($opcion == "add"){
            $this->keys[$this->items] = null;
            $this->values[$this->items] = null;
            $this->items++;
        }else{
            for ($i = $opcion; $i < $this->items - 1; $i++){
                $this->keys[$i] = $this->keys[$i + 1];
                $this->values[$i] = $this->values[$i +1 ];
            }
            $this->items--;
            unset($this->keys[$this->items]);
            unset($this->values[$this->items]);
        }
    }

}

<?php

namespace App\Http\Controllers\Organization\Event;

use App\Http\Controllers\Controller;
use App\Models\{Event, User};
use App\Services\EventService;
use Illuminate\Http\Request;

class EventSubscriptionController extends Controller
{
    public function store(Event $event, Request $request){
        $user = User::findOrFail($request->user_id);

        if(EventService::userSubscribedOnEvent($user, $event)){
            return back()->with('warning', 'Este participante já está inscrito nesse Evento!');
        }

        if(EventService::eventEndDateHasPassed($event)){
            return back()->with('warning', 'Operação Inválida o Evento ja ocorreu.');
        }
        if(EventService::eventParticipantsLimitHasReached($event)){
            return back()->with('warning', 'O limite de entradas para esse Evento ja foi Preenchida!');
        }

        $user->events()->attach($event->id);

        return back()->with('success', 'Inscrição no Evento realizada com Sucesso!');
    }

    public function destroy(EVent $event, User $user){
        if(EventService::eventEndDateHasPassed($event)){
            return back()->with('warning', 'Operação Inválida o Evento ja ocorreu.');
        }

        if(!EventService::userSubscribedOnEvent($user, $event)){
            return back()->with('warning', 'O Participante não está inscrito no evento!');
        }

        $user->events()->detach($event->id);

        return back()->with('success', 'Inscrição no Evento removida com sucesso!');

    }
}

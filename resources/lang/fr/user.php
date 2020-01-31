<?php
return [
  'response' => [
      'add' => [
        'ok' => 'ok',
        'not_ok' => 'Erreur lors de l\'inscription'
      ],
      'connect' => [
          'not_ok' => 'Connexion impossible'
      ],
      'update' => [
          'ok' => 'Mise à jour des infos réussi',
          'not_ok' => 'Erreur lors de la mise à jour des infos'
      ],
      'delete' => [
          'ok' => 'Suppression effectué',
          'not_ok' => 'Erreur lors de la suppression du compte',
          'password' => 'le mot de passe ne correspond pas'
      ],
      'newsy' => [
          'too_many_request' => 'Trop grand nombre de requetes effectuer dans les :time dernieres minutes'
      ]
  ]
];
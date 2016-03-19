<?php

namespace EmanueleMinotto\SafeBrowsingBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Constraint used for URL safety evaluation.
 *
 * @author Emanuele Minotto <minottoemanuele@gmail.com>
 *
 * @Annotation
 */
class Safe extends Constraint
{
    /**
     * Messaggio per la validazione.
     *
     * @var string
     */
    public $message = 'This URL is not safe (%response%).';

    /**
     * {@inheritdoc}
     */
    public function validatedBy()
    {
        return 'safe';
    }
}

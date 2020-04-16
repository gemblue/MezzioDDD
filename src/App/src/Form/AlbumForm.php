<?php

declare(strict_types=1);

namespace App\Form;

use Laminas\Form\Element\Submit;
use Laminas\Form\Element\Text;
use Laminas\Form\Form;
use Laminas\InputFilter\InputFilterProviderInterface;
use Laminas\Validator\StringLength;

class AlbumForm extends Form implements InputFilterProviderInterface
{
    public function __construct()
    {
        parent::__construct('album-form');

        $this->init();
    }

    public function init()
    {
        $this->add([
            'type'    => Text::class,
            'name'    => 'artist',
            'options' => [
                'label' => 'Artist',
            ],
        ]);

        $this->add([
            'type'    => Text::class,
            'name'    => 'title',
            'options' => [
                'label' => 'Title',
            ],
        ]);

        $this->add([
            'name'       => 'save',
            'type'       => Submit::class,
            'attributes' => [
                'value' => 'Save',
            ],
        ]);
    }

    public function getInputFilterSpecification(): array
    {
        return [
            [
                'name'       => 'artist',
                'required'   => true,
                'filters'    => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
                'validators' => [
                    [
                        'name'    => StringLength::class,
                        'options' => [
                            'min' => 1,
                            'max' => 255,
                        ],
                    ],
                ],
            ],
            [
                'name'       => 'title',
                'required'   => true,
                'filters'    => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
                'validators' => [
                    [
                        'name'    => StringLength::class,
                        'options' => [
                            'min' => 1,
                            'max' => 255,
                        ],
                    ],
                ],
            ],
        ];
    }
}

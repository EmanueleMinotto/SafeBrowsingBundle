Validator Constraints
=====================

A validator constraint is provided, ``Safe`` checks if an URL or domain isn't
safe (if the value isn't null or blank).

To know the possible outputs, see `Response Body`_.

Basic Usage
-----------

Annotations:

.. code-block:: php

    // src/AppBundle/Entity/Link.php
    namespace AppBundle\Entity;

    use EmanueleMinotto\SafeBrowsingBundle\Validator\Constraints\Safe;

    class Link
    {
        /**
         * @Safe
         */
         protected $urlOrDomain;
    }

YAML:

.. code-block:: yaml

    # src/AppBundle/Resources/config/validation.yml
    AppBundle\Entity\Link:
        properties:
            urlOrDomain:
                - EmanueleMinotto\SafeBrowsingBundle\Validator\Constraints\Safe: ~

XML:

.. code-block:: xml

    <!-- src/AppBundle/Resources/config/validation.xml -->
    <?xml version="1.0" encoding="UTF-8" ?>
    <constraint-mapping xmlns="http://symfony.com/schema/dic/constraint-mapping"
        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:schemaLocation="http://symfony.com/schema/dic/constraint-mapping http://symfony.com/schema/dic/constraint-mapping/constraint-mapping-1.0.xsd">

        <class name="AppBundle\Entity\Link">
            <property name="urlOrDomain">
                <constraint name="EmanueleMinotto\SafeBrowsingBundle\Validator\Constraints\Safe" />
            </property>
        </class>
    </constraint-mapping>

PHP:

.. code-block:: php

    // src/AppBundle/Entity/Link.php
    namespace AppBundle\Entity;

    use EmanueleMinotto\SafeBrowsingBundle\Validator\Constraints\Safe;
    use Symfony\Component\Validator\Mapping\ClassMetadata;

    class Link
    {
        public static function loadValidatorMetadata(ClassMetadata $metadata)
        {
            $metadata->addPropertyConstraint('urlOrDomain', new Safe());
        }
    }

Options
-------

message
~~~~~~~

**type**: ``string`` **default**: ``This URL is not safe (%response%).``

This message is shown if the URL or domain isn't safe.

.. _`Response Body`: https://developers.google.com/safe-browsing/lookup_guide#HTTPGETRequest

Installation
============

Step 1: Download the Bundle
---------------------------

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

.. code-block:: bash

    $ composer require emanueleminotto/safe-browsing-bundle

This command requires you to have Composer installed globally, as explained
in the `installation chapter`_ of the Composer documentation.

Step 2: Enable the Bundle
-------------------------

Then, enable the bundle by adding it to the list of registered bundles
in the ``app/AppKernel.php`` file of your project:

.. code-block:: php

    <?php
    // app/AppKernel.php

    // ...
    class AppKernel extends Kernel
    {
        public function registerBundles()
        {
            $bundles = array(
                // ...

                new EmanueleMinotto\SafeBrowsingBundle\SafeBrowsingBundle(),
            );

            // ...
        }

        // ...
    }

Step 3: Configuration
---------------------

This bundle requires a minimum configuration:

.. code-block:: yaml

    # app/config/config.yml
    safe_browsing:
        configs:
            key: [API key]

To obtain the API key read the `Getting Started`_.

Readings
--------

- `Configuration Reference`_
- `Validator Constraint`_

.. _`installation chapter`: https://getcomposer.org/doc/00-intro.md
.. _`Configuration Reference`: https://github.com/EmanueleMinotto/SafeBrowsingBundle/tree/master/src/Resources/doc/configuration-reference.rst
.. _`Validator Constraint`: https://github.com/EmanueleMinotto/SafeBrowsingBundle/tree/master/src/Resources/doc/validator-constraints.rst
.. _`Getting Started`: https://developers.google.com/safe-browsing/lookup_guide#GettingStarted

Configuration Reference
=======================

.. code-block:: yaml

    # app/config/config.yml
    safe_browsing:
        configs:
            client: app   # The client name
            appver: 1.0.0 # The version of the client
            key:          # API key, required
            pver: 3.1     # The protocol version supported, could be 3.0 or 3.1

        cache: ~       # Caching service
        http_client: ~ # HTTP client service

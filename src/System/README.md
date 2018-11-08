# System

Any kind of configuration - like for WebPack - or other system-internal files that should not be changed by the user, should go into this folder.

* Create a folder for each service/program that stores it's sensitive configuration here.
* Make use of the `BIRD3.yml` file whenever possible to avoid hardcoded configs.
* Try to be structure agnostic - it may change sometimes.

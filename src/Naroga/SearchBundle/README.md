Naroga's Search Bundle
======================

This is a search bundle, developed for exclusive usage of DistantJobHire.

Installation
------------

To install this package, require it using composer:

    $ composer require naroga/search-bundle dev-master
    
Usage
-----

To use this package, retrieve the service from the DIC:
 
    $search = $this->get('naroga.search');
    $search->addFile('name', 'content');
    $search->searchFile('cont'); //Retrieves a File('name', 'content') object.
    
 * This uses the default search engine (elasticsearch). If you wish to tweak this library and use
 another search engine, read the [documentation](Docs/index.md).
    
License
-------

This project is proprietary and thus cannot be used, copied, modified or distributed without explicit written 
consent from the owner (DistantJobHire).
.. include:: ../Includes.txt

.. _administrator:

====================
Administrator Manual
====================

.. hint::

   Please read the `documentation of the TYPO3 PersistedPatternMapper <https://docs.typo3.org/m/typo3/reference-coreapi/master/en-us/ApiOverview/Routing/AdvancedRoutingConfiguration.html#persistedpatternmapper>`_ for details.

.. _administrator-properties:

Properties
==========

.. _confType:

type
""""
.. container:: table-row

   Property
         type
   Data type
         string
   Description
         The aspect type is *CcPersistedPatternMapper*.
   Default
         N/A

.. _confTableName:

tableName
"""""""""
.. container:: table-row

   Property
         tableName
   Data type
         string
   Description
         The database table.
   Default
         N/A

.. _confRouteFieldResult:

routeFieldResult
""""""""""""""""
.. container:: table-row

   Property
         routeFieldResult
   Data type
         string
   Description
         Table field(s) to use for path segment alias.
   Default
         N/A

.. _confRouteFieldHandles:

routeFieldHandles
"""""""""""""""""
.. container:: table-row

   Property
         routeFieldHandles
   Data type
         string
   Description
         Comma separated list to convert the field data.
         Possible values are: asciiTranslit, toLowerCase, specialCharsRemove, trim, filter, md5
   Default
         N/A

.. _confFilter:

filter
""""""
.. container:: table-row

   Property
         filter
   Data type
         string
   Description
         Regular expression to filter the path segment.
         This can be used to limit the length, see the example below.
   Default
         /(.*)/

.. _confSpecialCharsRemoveSearch:

specialCharsRemoveSearch
""""""""""""""""""""""""
.. container:: table-row

   Property
         specialCharsRemoveSearch
   Data type
         string
   Description
         Regular expression to search the path segment.
   Default
         /\[^a-zA-Z0-9\_\]+/

.. _confSpecialCharsRemoveReplace:

specialCharsRemoveReplace
"""""""""""""""""""""""""
.. container:: table-row

   Property
         specialCharsRemoveReplace
   Data type
         string
   Description
         Replacement string of search.
   Default
         \-

.. _administrator-example:

Example
=======

.. code-block:: yaml

   routeEnhancers:
     CcExamplePlugin:
       type: Extbase
       extension: CcExample
       plugin: Fe
       routes:
         - { routePath: '{title}',_controller: 'View::show',_arguments: {'title': 'item'} }
       defaultController: 'View::show'
       aspects:
         title:
           type: CcPersistedPatternMapper
           tableName: 'tx_ccexample_item'
           routeFieldResult: '{title}-{location}'
           routeFieldHandles: 'asciiTranslit,toLowerCase,specialCharsRemove,trim,filter,md5'
           filter: '/^(.{0,40})/'
   #        specialCharsRemoveSearch: '/[^a-zA-Z0-9_]+/'
   #        specialCharsRemoveReplace: '-'

.. _administrator-extConf:

Extension configuration
=======================

.. _confExpire:

expire
""""""
.. container:: table-row

   Property
         expire
   Data type
         int
   Description
         Days until pathsegments will expire.
   Default
         N/A

.. _confRefresh:

refresh
"""""""
.. container:: table-row

   Property
         refresh
   Data type
         int
   Description
         Refresh pathsegments after these days. This value should be lower than the expire days.
   Default
         N/A

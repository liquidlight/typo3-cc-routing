.. include:: ../Includes.txt

.. _introduction:

============
Introduction
============

TYPO3 uses routes to generate `speaking URLs <https://docs.typo3.org/m/typo3/reference-coreapi/master/en-us/ApiOverview/Routing/AdvancedRoutingConfiguration.html>`_ since version 9. Up to TYPO3 8.7 the extension *realurl* by Dmitry Dulepov was used.
*realurl* stored pathsegments of different fields in the database table *tx_realurl_uniqalias*.

.. _what-it-does:

What does it do?
================

This extensions provides the route aspect type **CcPersistedPatternMapper**, which maps database fields such as news title to the path segment and persists it to the database. So it is possible to convert special chars, umlauts etc.

.. important::

   Please read the :ref:`administrator manual <administrator>` for usage and examples.

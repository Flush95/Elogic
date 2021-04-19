define([
    'jquery',
    'ko',
    'uiComponent'], function ($, ko, Component) {
    'use strict';
    return Component.extend({
        defaults: {
            template: 'Elogic_StoreLocator/customfieldtemp'
        },
        initialize: function () {
            this._super();

            return this;
        },
    });});

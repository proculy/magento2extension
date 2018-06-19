/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
define([
    'underscore',
    'Magento_Ui/js/grid/columns/column',
], function (_, Column) {
    'use strict';

    return Column.extend({

        /*eslint-disable eqeqeq*/
        /**
         * Retrieves label associated with a provided value.
         *
         * @returns {String}
         */
        getLabel: function () {
            var options = this.options || [],
                values = this._super(),
                label = [];
            
            if (_.isString(values)) {
                values = values.split(',');
            }

            if (!Array.isArray(values)) {
                values = [values];
            }

            values = values.map(function (value) {
                return value + '';
            });

            options.forEach(function (item) {
                if (_.contains(values, item.value + '')) {
                    label.push(item.label);
                    // label.push('<div style="color:#FFF;font-weight:bold;background:#F55804;border-radius:8px;width:100%">' + item.label + '</div>');
                }
            });
            
            return label.join(', ');
        },
        /**
         * Returns list of classes that should be applied to a field.
         *
         * @returns {Object}
         */
        getFieldClass: function (row) {
            var options={notstart:false,start:false,inprogress:false,done:false};
            if(row && row['status']){
                switch(row['status'].toString()){
                    case '0':
                        options['notstart']=true;
                        this.fieldClass=options;
                        break;
                    case '1':
                        options['start']=true;
                        this.fieldClass=options;
                        break;
                    case '2':
                        options['inprogress']=true;
                        this.fieldClass=options;
                        break;
                    case '3':
                        options['done']=true;
                        this.fieldClass=options;
                        break;
                }
            }
            return this.fieldClass;
        }

        /*eslint-enable eqeqeq*/
    });
});

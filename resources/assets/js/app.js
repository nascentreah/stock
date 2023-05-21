/**
 * First, we will load all of this project's Javascript utilities and other
 * dependencies. Then, we will be ready to develop a robust and powerful
 * application frontend using useful Laravel and JavaScript libraries.
 */

require('./bootstrap');

import { config } from './utils'

import AssetsTable from './components/assets-table.vue'
import AssetChart from './components/asset-chart.vue'
import CompetitionTrade from './components/competition-trade.vue'
import CompetitionForm from './components/competition-form.vue'
import DataFeed from './components/data-feed.vue'
import ImageUploadInput from './components/image-upload-input.vue'
import LocaleSelect from './components/locale-select.vue'
import LogOutButton from './components/log-out-button.vue'
import Message from './components/message.vue'
import LoadingForm from './components/loading-form.vue'
import Chat from './components/chat.vue'

Vue.component('assets-table',           AssetsTable);
Vue.component('asset-chart',            AssetChart);
Vue.component('competition-trade',      CompetitionTrade);
Vue.component('competition-form',       CompetitionForm);
Vue.component('data-feed',              DataFeed);
Vue.component('image-upload-input',     ImageUploadInput);
Vue.component('locale-select',          LocaleSelect);
Vue.component('log-out-button',         LogOutButton);
Vue.component('message',                Message);
Vue.component('loading-form',           LoadingForm);
Vue.component('chat',                   Chat);

Vue.prototype.$eventBus = new Vue();

// global and the only one Vue instance
const app = new Vue({
    el: '#app'
});

// custom locale settings for numeral.js
numeral.register('locale', 'custom', {
    delimiters: {
        decimal: String.fromCharCode(config('settings.number_decimal_point')),
        thousands: String.fromCharCode(config('settings.number_thousands_separator'))
    },
    abbreviations: {
        thousand: 'k',
        million: 'm',
        billion: 'b',
        trillion: 't'
    },
    ordinal : function (number) {
        return '';
    },
    currency: {
        symbol: ''
    }
});
numeral.locale('custom');

if (!Number.prototype.integer) {
    Number.prototype.integer = function () {
        return numeral(this).format('0,0');
    };
}

if (!Number.prototype.decimal) {
    Number.prototype.decimal = function () {
        var num = numeral(this);
        var formatted = num.format('0,0.00');
        return formatted!=='NaN' ? formatted : parseFloat(this).toFixed(2);
    };
}

if (!Number.prototype.variableDecimal) {
    Number.prototype.variableDecimal = function () {
        var format;
        var num = numeral(this);
        var n = Math.abs(num.value());
        if (n >= 10) {
            format = '0,0.00';
        } else if (0.1 <= n && n < 10) {
            format = '0.0000';
        } else if (n < 0.1) {
            format = '0.00000000';
        }
        // for small numbers like  9.2e-7 numeral.format() will return NaN, so need a workaround
        var formatted = num.format(format);
        return formatted!=='NaN' ? formatted : parseFloat(this).toFixed(8);
    };
}

if (!Number.prototype.percentage) {
    Number.prototype.percentage = function () {
        return this.decimal()+'%';
    };
}

// Semantic UI controls initizalization
$('.ui.dropdown').not('.editable').dropdown();
$('.ui.editable.dropdown').dropdown({ allowAdditions: true });
$('.ui.checkbox').checkbox();
$('.ui.accordion').accordion();
$('.ui.popup-trigger').popup({ on: 'click' });

// Semantic search named API routes
$.fn.api.settings.api = {
    searchAssets: '/assets/search/{query}',
    searchCompetitionAssets: '/competitions/{competition}/assets/search/{query}'
};

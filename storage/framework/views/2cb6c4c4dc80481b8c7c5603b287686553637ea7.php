

<?php $__env->startSection('title'); ?>
    <?php echo e($competition->title); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <?php echo $__env->make('pages.frontend.competitions.header', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>

    <data-feed></data-feed>
    <competition-trade :user="<?php echo e(auth()->user()); ?>" :competition="<?php echo e($competition); ?>" :asset="<?php echo e($asset); ?>" :currencies="<?php echo e($currencies); ?>" inline-template>
        <div class="ui stackable grid container">
            <div class="eleven wide column">
                <div class="ui one column grid">
                    <div class="column">
                        <div id="asset-search" class="ui tablet-and-below-center  <?php echo e($inverted); ?> search">
                            <div class="ui icon input">
                                <input class="prompt" type="text" placeholder="<?php echo e(__('app.search')); ?>">
                                <i class="search icon"></i>
                            </div>
                        </div>
                    </div>
                    <div class="center aligned column">
                        <template v-if="selectedAsset.symbol_ext">
                            <asset-chart :asset="selectedAsset" color="<?php echo e($settings->color); ?>" :currency="currencies[selectedAsset.currency_id].symbol_native" :inverted="<?php echo e($inverted ? 'false' : 'true'); ?>"></asset-chart>
                        </template>
                    </div>
                    <div class="center aligned column">
                        <template v-if="selectedAsset.symbol_ext">
                            <h4 class="ui header">
                                <i :class="[selectedAsset.market.country_code, 'flag']"></i> {{ selectedAsset.market.name }}
                                <span :class="['ui basic label', selectedAsset.market.open ? 'green' : 'red']">{{ selectedAsset.market.open ? this.__('app.market_open') : this.__('app.market_closed') }}</span>
                            </h4>
                            <div id="current-asset" class="ui <?php echo e($inverted); ?> statistic">
                                <div class="value">
                                    <img :src="selectedAsset.logo_url" class="ui inline image">
                                    <span id="current-asset-symbol-native">{{ currencies[selectedAsset.currency_id].symbol_native }}</span><span>{{ selectedAsset.price.variableDecimal() }}</span>
                                </div>
                                <div class="label">
                                    {{ selectedAsset.name }} ({{ selectedAsset.symbol }})
                                </div>
                            </div>
                        </template>
                        <template v-else>
                            <div id="asset-info-loader" class="ui active centered inline loader"></div>
                        </template>
                        <div id="trade-form" class="ui <?php echo e($inverted); ?> form">
                            <div class="fields">
                                <div v-cloak class="six wide field">
                                    <input v-model="input.volume" name="volume" placeholder="<?php echo e($competition->volume_min); ?> &mdash; <?php echo e($competition->volume_max); ?>" type="text" autocomplete="off">
                                    <div v-if="!input.volume || isNaN(input.volume) || input.volume <= 0" class="ui pointing label">
                                        <?php echo e(__('app.input_volume')); ?>

                                    </div>
                                    <div v-else :class="['ui basic pointing label', {green: margin <= freeMargin, red: margin > freeMargin}]">
                                        <?php echo e(__('app.margin_required')); ?>: {{ _margin }} <?php echo e($competition->currency->code); ?>

                                        <span v-if="margin > freeMargin"> (<?php echo e(__('app.free_margin')); ?>: {{ _freeMargin }}) <?php echo e($competition->currency->code); ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="ui big buttons">
                                <button class="ui positive trade button" :class="[{ disabled: margin < 0 || margin > freeMargin || assets[selectedAsset.symbol_ext].price==0 }, this.loading.openTrade ? 'disabled loading' : '']" @click="openTrade" data-direction="<?php echo e(\App\Models\Trade::DIRECTION_BUY); ?>"><?php echo e(__('app.buy')); ?></button>
                                <div class="or"></div>
                                <button class="ui negative trade button" :class="[{ disabled: margin < 0 || margin > freeMargin || assets[selectedAsset.symbol_ext].price==0 }, this.loading.openTrade ? 'disabled loading' : '']" @click="openTrade" data-direction="<?php echo e(\App\Models\Trade::DIRECTION_SELL); ?>"><?php echo e(__('app.sell')); ?></button>
                            </div>
                        </div>
                        <template v-if="selectedAsset.symbol_ext">
                            <div v-if="error" class="ui red basic pointing label">
                                <?php echo e(__('app.error')); ?>: {{ error }}
                            </div>
                        </template>
                    </div>
                    <div class="column">
                        <template v-if="openTrades.length">
                            <table id="open-trades-table" class="ui basic tablet stackable <?php echo e($inverted); ?> table">
                                <thead>
                                <tr>
                                    <th><?php echo e(__('app.asset')); ?></th>
                                    <th class="right aligned"><?php echo e(__('app.quantity')); ?></th>
                                    <th class="right aligned"><?php echo e(__('app.open_price')); ?>, <?php echo e($competition->currency->code); ?></th>
                                    <th class="right aligned"><?php echo e(__('app.current_price')); ?>, <?php echo e($competition->currency->code); ?></th>
                                    <th class="right aligned"><?php echo e(__('app.margin')); ?>, <?php echo e($competition->currency->code); ?></th>
                                    <th class="right aligned"><?php echo e(__('app.pnl')); ?>, <?php echo e($competition->currency->code); ?></th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(trade,tradeIndex) in openTrades">
                                    <td data-title="<?php echo e(__('app.asset')); ?>" class="nowrap">
                                        <div class="trade-symbol">
                                            <img :src="trade.asset.logo_url" class="ui inline image">
                                            <span class="tooltip" :data-tooltip="trade.asset.name + ' (' + trade.asset.market.name + ')'" data-position="top left" <?php echo e($inverted ? 'data-inverted="false"' : ''); ?>>
                                                {{ trade.asset.symbol_ext }}
                                            </span>
                                            <span v-if="trade.direction == <?php echo e(\App\Models\Trade::DIRECTION_BUY); ?>" class="ui tiny basic green label">
                                                <i class="arrow up icon"></i>
                                                <?php echo e(__('app.trade_direction_' . \App\Models\Trade::DIRECTION_BUY)); ?>

                                            </span>
                                            <span v-else class="ui tiny basic red label">
                                                <i class="arrow down icon"></i>
                                                <?php echo e(__('app.trade_direction_' . \App\Models\Trade::DIRECTION_SELL)); ?>

                                            </span>
                                        </div>
                                        <div class="secondary-info">
                                            <i class="calendar outline icon"></i>
                                            {{ trade.created_at }}
                                        </div>
                                    </td>
                                    <td data-title="<?php echo e(__('app.quantity')); ?>" class="right aligned">{{ trade.quantity.decimal() }}</td>
                                    <td data-title="<?php echo e(__('app.open_price')); ?>" class="right aligned">{{ trade.price_open.variableDecimal() }}</td>
                                    <td data-title="<?php echo e(__('app.current_price')); ?>" class="right aligned">{{ price(trade.asset).variableDecimal() }}</td>
                                    <td data-title="<?php echo e(__('app.margin')); ?>" class="right aligned">
                                        <span class="tooltip" :data-tooltip="marginFormula(trade)" <?php echo e($inverted ? 'data-inverted="false"' : ''); ?>>
                                            {{ trade.margin.variableDecimal() }}
                                        </span>
                                    </td>
                                    <td data-title="<?php echo e(__('app.pnl')); ?>" :class="[{ positive: unrealizedPnl(trade)>0, negative: unrealizedPnl(trade)<0 }, 'right aligned']">{{ unrealizedPnl(trade).decimal() }}</td>
                                    <td class="right aligned tablet-and-below-center">
                                        <button class="ui <?php echo e($settings->color); ?> small button" :class="loading.closeTrades.indexOf(trade.id) > -1 ? 'disabled loading' : ''" @click="closeTrade" :data-id="trade.id" :data-index="tradeIndex"><?php echo e(__('app.close')); ?></button>
                                    </td>
                                </tr>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="5" class="bold right aligned"><?php echo e(__('app.balance')); ?></td>
                                    <td colspan="2">{{ _balance }} <?php echo e($competition->currency->code); ?></td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="bold right aligned"><?php echo e(__('app.pnl')); ?></td>
                                    <td colspan="2">{{ _totalUnrealizedPnl }} <?php echo e($competition->currency->code); ?></td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="bold right aligned">
                                        <span data-tooltip="<?php echo e(__('app.equity_tooltip')); ?>" <?php echo e($inverted ? 'data-inverted="false"' : ''); ?>>
                                            <i class="question circle outline tooltip icon"></i>
                                        </span>
                                        <?php echo e(__('app.equity')); ?>

                                    </td>
                                    <td colspan="2">{{ _equity }} <?php echo e($competition->currency->code); ?></td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="bold right aligned"><?php echo e(__('app.margin')); ?></td>
                                    <td colspan="2">{{ _totalMargin }} <?php echo e($competition->currency->code); ?></td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="bold right aligned">
                                        <span data-tooltip="<?php echo e(__('app.free_margin_tooltip')); ?>" <?php echo e($inverted ? 'data-inverted="false"' : ''); ?>>
                                            <i class="question circle outline tooltip icon"></i>
                                        </span>
                                        <?php echo e(__('app.free_margin')); ?>

                                    </td>
                                    <td colspan="2">{{ _freeMargin }} <?php echo e($competition->currency->code); ?></td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="bold right aligned">
                                        <span data-tooltip="<?php echo e(__('app.margin_level_tooltip')); ?>" <?php echo e($inverted ? 'data-inverted="false"' : ''); ?>>
                                            <i class="question circle outline tooltip icon"></i>
                                        </span>
                                        <?php echo e(__('app.margin_level')); ?>

                                    </td>
                                    <td colspan="2">
                                        {{ _marginLevel }}
                                        <span v-if="marginLevel < competition.min_margin_level" class="tooltip" data-tooltip="<?php echo e(__('app.margin_level_warning')); ?>" <?php echo e($inverted ? 'data-inverted="false"' : ''); ?>>
                                            <i class="red exclamation triangle icon"></i>
                                        </span>
                                    </td>
                                </tr>
                                </tfoot>
                            </table>
                        </template>
                        <template v-else>
                            <div class="ui message"><?php echo e(__('app.no_open_trades')); ?></div>
                        </template>
                    </div>
                </div>
            </div>
            <div class="five wide column">
                <template v-if="participants.length">
                    <div class="ui <?php echo e($inverted); ?> segment">
                        <h2 class="ui dividing tablet-and-below-center <?php echo e($inverted); ?> header">
                            <?php echo e(__('app.leaderboard')); ?>

                        </h2>
                        <table id="competition-leaderboard" class="ui very basic tablet stackable <?php echo e($inverted); ?> table">
                            <tbody>
                            <tr v-for="(participant,participantIndex) in participants.slice(0,10)" :class="{bold: participant.id==user.id}">
                                <td class="tablet-and-below-center">{{ participant.data.place ? participant.data.place : participantIndex+1 }}</td>
                                <td class="tablet-and-below-center">
                                    <a :href="'/users/' + participant.id">
                                        <img class="ui avatar image" :src="participant.avatar_url"> {{ participant.name }}
                                    </a>
                                </td>
                                <td class="right aligned tablet-and-below-center">
                                    <div><?php echo e($competition->currency->symbol_native); ?>{{ participant.data.current_balance.decimal() }}</div>
                                    <div :class="['balance-change', participant.data.pnl > 0 ? 'green' : 'red']">
                                        {{ participant.data.current_balance !=0 && participant.data.pnl != 0 ? (participant.data.pnl).decimal() : '' }}
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                            <tfoot v-if="participants.length > 10">
                            <tr>
                                <td colspan="3" class="right aligned tablet-and-below-center">
                                    <a href="<?php echo e(route('frontend.competitions.leaderboard', $competition)); ?>">
                                        <?php echo e(__('app.view_all')); ?>

                                    </a>
                                </td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </template>
            </div>
        </div>
    </competition-trade>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.frontend', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
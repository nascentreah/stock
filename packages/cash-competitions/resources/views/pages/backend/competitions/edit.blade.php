<div class="field {{ $errors->has('fee') ? 'error' : '' }}">
    <label>{{ __('app.fee') }} <span data-tooltip="{{ __('app.fee_hint') }}"><i class="question circle outline tooltip icon"></i></span></label>
    <div class="ui right labeled input">
        <input v-model="fields.fee" type="text" name="fee" placeholder="{{ __('app.fee') }}" required autofocus {{ $editable ? '' : 'disabled' }}>
        <div class="ui basic label">{{ $competition->currency->code }}</div>
    </div>
</div>
<h4 class="ui dividing header">{{ __('app.payout_structure') }}</h4>
<div v-for="(amount, i) in fields.payouts.amounts" class="field {{ $errors->has('fee') ? 'error' : '' }}">
    <label>@{{ i+1 }} {{ __('app.payout_place_label') }}</label>
    <div class="ui left action right labeled input">
        <span v-if="i == fields.payouts.amounts.length-1 && fields.editable" class="ui icon button" @click="removePayout">
            <i class="minus icon"></i>
        </span>
        <span v-else class="ui disabled icon button">
            <i class="icon"></i>
        </span>
        <select v-model="fields.payouts.types[i]" name="payouts_types[]" class="ui competition-payout-type dropdown" :disabled="!fields.editable">
            <option value="flat">{{ __('app.payout_type_flat') }}</option>
            <option value="percentage">{{ __('app.payout_type_percentage') }}</option>
        </select>
        <input v-model="fields.payouts.amounts[i]" type="text" name="payouts_amounts[]" placeholder="{{ __('app.fee') }}" required autofocus :disabled="!fields.editable">
        <div v-if="fields.payouts.types[i]=='flat'" class="ui basic label">{{ $competition->currency->code }}</div>
        <div v-else class="ui basic label">%</div>
    </div>
</div>
<template v-if="fields.editable">
    <div v-if="fields.payouts.amounts.length < fields.maxParticipants" class="field">
        <span class="ui icon button" @click="addPayout">
        <i class="plus icon"></i>
        {{ __('app.add_payout') }}
        </span>
    </div>
    <div v-else class="ui visible warning message">
        {{ __('app.input_max_participants') }}
    </div>
</template>
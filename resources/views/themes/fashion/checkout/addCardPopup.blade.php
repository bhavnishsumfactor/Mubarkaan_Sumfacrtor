<div class="form form-floating" id="YK-saveCard">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group form-floating__group">
                <input type="text" name="number" class="form-control form-floating__field" placeholder="" id="number">
                <label class="form-floating__label">{{ __('LBL_CARD_NUMBER') }}</label>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group form-floating__group">
                <input type="text" name="name" class="form-control form-floating__field" placeholder="" id="name">
                <label class="form-floating__label">{{ __('LBL_CARDHOLDERS_NAME') }}</label>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group form-floating__group">
                <select class="custom-select" id="exp_month" name="exp_month">
                    <option value="">{{ __('LBL_MONTH') }}</option>
                    @for ($i=1; $i<=12; $i++)
                        <option value="{{ sprintf('%02d', $i) }}">{{ sprintf('%02d', $i) }}</option>
                    @endfor
                </select>
            </div>
        </div>
        @php 
            $currentYear = \Carbon\Carbon::now()->year;
        @endphp
        <div class="col-md-4">
            <div class="form-group form-floating__group">
                <select class="custom-select" id="exp_year" name="exp_year">
                    <option value="">{{ __('LBL_YEAR') }}</option>
                    @for ($j = $currentYear; $j <= ($currentYear+10); $j++)
                        <option value="{{ $j }}">{{ $j }}</option>
                    @endfor
                </select> 
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group form-floating__group">
                <input type="text" name="cvv" class="form-control form-floating__field" placeholder="" id="cvv">
                <label class="form-floating__label">{{ __('LBL_CVV') }}</label>
            </div>
        </div>
    </div>
    @if(Auth::check())
    <div class="row">
        <div class="col-md-12">
            <label class="checkbox">
                <input title="" type="checkbox" name="save-card" value="1" checked="" >{{ __('LBL_SAVE_CARD') }}  <i class="input-helper"></i>
            </label>
        </div>
    </div>
    @endif
</div>

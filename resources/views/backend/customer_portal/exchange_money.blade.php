@extends('layouts.app')

@section('content')
<div class="row">
	<div class="col-lg-8 offset-lg-2">
		<div class="card">
			<div class="card-header">
				<h4 class="header-title text-center">{{ _lang('Exchange Money') }}</h4>
			</div>
			<div class="card-body">
			    <form method="post" class="validate" autocomplete="off" action="{{ route('transfer.exchange_money') }}" enctype="multipart/form-data">
					{{ csrf_field() }}

					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">{{ _lang('Exchange From') }}</label>
								<select class="form-control auto-select select2" data-selected="{{ old('currency_from') }}" name="currency_from" id="currency_from" required>
									<option value="">{{ _lang('Select One') }}</option>
									{{ create_option('currency','id','name','',array('status=' => 1)) }}
								</select>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">{{ _lang('Exchange To') }}</label>
								<select class="form-control auto-select select2" data-selected="{{ old('currency_to') }}" name="currency_to" id="currency_to" required>
									<option value="">{{ _lang('Select One') }}</option>
									{{ create_option('currency','id','name','',array('status=' => 1)) }}
								</select>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">{{ _lang('Amount') }}</label>
								<input type="text" class="form-control float-field" name="amount" id="amount" value="{{ old('amount') }}" required>
							</div>
						</div>

						<div class="col-lg-6">
							<div class="form-group">
								<label class="control-label">{{ _lang('Exchanged Amount') }}</label>
								<input type="text" class="form-control float-field" id="exchange_amount" value="" readonly>
							</div>
						</div>

						<div class="col-md-12">
							<div class="form-group">
								<label class="control-label">{{ _lang('Note') }}</label>
								<textarea class="form-control" name="note">{{ old('note') }}</textarea>
							</div>
						</div>

						<div class="col-md-12">
							<h6 class="text-info text-center"><b>{{ get_option('exchange_fee_type') == 'percentage' ? get_option('exchange_fee').'%' : currency().get_option('exchange_fee') }} {{ _lang('exchange fee will apply') }}</b></h6>
						</div>

						<div class="col-md-12 mt-4">
							<div class="form-group">
								<button type="submit" class="btn btn-primary btn-lg btn-block"><i class="icofont-check-circled"></i> {{ _lang('Exchange Money') }}</button>
							</div>
						</div>
					</div>
			    </form>
			</div>
		</div>
    </div>
</div>
@endsection

@section('js-script')
<script>
(function ($) {
  "use strict";

  $(document).on('change','#currency_from, #currency_to', function(){
	$("#amount").val('');
	$("#exchange_amount").val('');
  });

  $(document).on('keyup','#amount', function(){
	  	var from = $("#currency_from").val();
	  	var to = $("#currency_to").val();
	  	var amount = $(this).val();

		if(from == '' || to == '' ){
			Swal.fire(
				'{{ _lang('Alert') }}',
				'{{ _lang('Please select exchange from and exchange to !') }}',
				'warning'
			);
			$(this).val('');
			return;
		}

		if(amount != ''){
			$.ajax({
				url: '{{ route('transfer.get_exchange_amount') }}/' + from + '/' + to + '/' + amount,
				beforeSend: function(){
					$("#submit-btn").prop('disabled', true);
				},success: function(data){
					var json = JSON.parse(JSON.stringify(data));
					$("#exchange_amount").val(json['amount'].toFixed(2));
					$("#submit-btn").prop('disabled', false);
				}
			});
		}else{
			$("#exchange_amount").val('');
		}
	
  });

})(jQuery);
</script>
@endsection



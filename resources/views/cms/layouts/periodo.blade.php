<div class="form-group">
  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Periodo<span class="required">*</span>
  </label>
  <div class="col-md-1 col-sm-1 col-xs-1">
    <select id="selected_year" name="selected_year" class="select2_single form-control">
      @foreach($gc->configurations as $config)
        @if($config->writable)
          <option id="{!!$config->year!!}" 
            @if($gc->create)
              @if($config->current) 
                selected 
              @endif 
            @else
              @if($config->year==$gc->entity_to_edit->year)
                selected 
              @endif 
            @endif            
            value="{!!$config->year!!}">{!!$config->year!!}</option>
        @endif
      @endforeach
    </select>
  </div>
</div>
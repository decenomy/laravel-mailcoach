<div>
    @error($name)
        <p class="form-error mb-1" role="alert">{{ $message }}</p>
    @enderror
    <div class="flex items-center">
        <x-date-field
            :name="$name . '[date]'"
            :value="$value->format('Y-m-d')"
            required
        />
        <span class="mx-3">at</span>
        <x-select-field
            :name="$name . '[hours]'"
            :options="$hourOptions"
            :value="$value->format('H')"
            required
        />
        <span class="mx-1">:</span>
        <x-select-field
            :name="$name . '[minutes]'"
            :options="$minuteOptions"
            :value="$value->format('i')"
            required
        />
    </div>
</div>

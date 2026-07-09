<!-- Business format selector -->
<div class="space-y-5" x-data="{ isSellBusiness: selectedType === 'sell_business' }" x-effect="isSellBusiness = selectedType === 'sell_business'">

    <div x-show="isSellBusiness" x-cloak class="border border-blue-100 bg-blue-50/50 rounded-xl p-5 space-y-5">
        <p class="text-sm font-medium text-slate-900">Стандарт карточки «Продажа бизнеса»</p>

        <!-- Listing format -->
        <div>
            <label for="listing_format" class="block text-sm font-medium text-slate-700 mb-1.5">
                Тип листинга <span class="text-red-500">*</span>
            </label>
            <select id="listing_format" name="listing_format"
                    class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-300 bg-white @error('listing_format') border-red-400 @enderror">
                <option value="">Выберите тип листинга</option>
                @foreach(\App\Enums\ListingFormat::cases() as $format)
                    <option value="{{ $format->value }}" {{ old('listing_format', $listing->listing_format?->value ?? '') === $format->value ? 'selected' : '' }}>
                        {{ $format->label() }}
                    </option>
                @endforeach
            </select>
            @error('listing_format')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Rent conditions -->
        <div>
            <label for="rent_conditions" class="block text-sm font-medium text-slate-700 mb-1.5">
                Аренда / помещение <span class="text-red-500">*</span>
            </label>
            <textarea id="rent_conditions" name="rent_conditions" rows="2"
                      placeholder="Срок аренды, ставка, площадь, условия..."
                      class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-300 resize-y @error('rent_conditions') border-red-400 @enderror"
            >{{ old('rent_conditions', $listing->rent_conditions ?? '') }}</textarea>
            @error('rent_conditions')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Included in deal -->
        <div>
            <label for="included_in_deal" class="block text-sm font-medium text-slate-700 mb-1.5">
                Что входит в сделку <span class="text-red-500">*</span>
            </label>
            <textarea id="included_in_deal" name="included_in_deal" rows="3"
                      placeholder="Оборудование, товар, бренд, ООО, доступы, клиентская база..."
                      class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-300 resize-y @error('included_in_deal') border-red-400 @enderror"
            >{{ old('included_in_deal', $listing->included_in_deal ?? '') }}</textarea>
            @error('included_in_deal')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Ready documents -->
        <div>
            <label for="ready_documents" class="block text-sm font-medium text-slate-700 mb-1.5">
                Какие документы готовы <span class="text-red-500">*</span>
            </label>
            <textarea id="ready_documents" name="ready_documents" rows="3"
                      placeholder="Выписка из ЕГР, договор аренды, бухгалтерская отчётность, акты инвентаризации..."
                      class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-300 resize-y @error('ready_documents') border-red-400 @enderror"
            >{{ old('ready_documents', $listing->ready_documents ?? '') }}</textarea>
            @error('ready_documents')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <label for="employees_count" class="block text-sm font-medium text-slate-700 mb-1.5">
                    Количество сотрудников <span class="text-red-500">*</span>
                </label>
                <input type="number" id="employees_count" name="employees_count"
                       value="{{ old('employees_count', $listing->employees_count ?? '') }}"
                       min="0" step="1" placeholder="0"
                       class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-300 @error('employees_count') border-red-400 @enderror"
                >
                @error('employees_count')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="sale_reason" class="block text-sm font-medium text-slate-700 mb-1.5">
                    Причина продажи <span class="text-red-500">*</span>
                </label>
                <input type="text" id="sale_reason" name="sale_reason"
                       value="{{ old('sale_reason', $listing->sale_reason ?? '') }}"
                       maxlength="255" placeholder="Переезд, смена деятельности..."
                       class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-300 @error('sale_reason') border-red-400 @enderror"
                >
                @error('sale_reason')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="bg-amber-50 border border-amber-100 rounded-lg p-3 text-xs text-amber-800">
            Для публикации «Продажи бизнеса» обязательны: город / регион, тип листинга, аренда / помещение,
            содержание сделки, штат, причина продажи, документы, а также хотя бы один финансовый показатель.
        </div>
    </div>
</div>

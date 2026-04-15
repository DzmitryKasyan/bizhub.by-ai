@extends('layouts.dashboard')

@section('title', 'Редактировать объявление')

@section('content')

<!-- Page Header -->
<div class="mb-6">
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
        <a href="{{ route('my-listings.index') }}" class="hover:text-blue-600 transition-colors">Мои объявления</a>
        <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-gray-700 truncate max-w-xs">{{ $listing->title }}</span>
    </div>
    <h1 class="text-2xl font-bold text-gray-900">Редактировать объявление</h1>
    <p class="text-gray-500 text-sm mt-1">
        Последнее изменение: {{ $listing->updated_at->format('d.m.Y в H:i') }}
    </p>
</div>

<!-- Validation Errors -->
@if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl p-4 mb-6">
        <p class="font-semibold text-sm mb-2">Пожалуйста, исправьте следующие ошибки:</p>
        <ul class="list-disc list-inside space-y-1 text-sm">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('my-listings.update', $listing->slug) }}"
      method="POST"
      enctype="multipart/form-data"
      x-data="{
          selectedType: '{{ old('type', $listing->type) }}',
          priceNegotiable: {{ old('price_negotiable', $listing->price_negotiable) ? 'true' : 'false' }},
          selectedCategory: '{{ old('category_id', $listing->category_id) }}',
      }">
    @csrf
    @method('PUT')

    <div class="space-y-6">

        <!-- Section 1: Main Info -->
        <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                <h2 class="font-semibold text-gray-900 flex items-center gap-2">
                    <span class="w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">1</span>
                    Основная информация
                </h2>
            </div>
            <div class="p-6 space-y-5">

                <!-- Type -->
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Тип объявления <span class="text-red-500">*</span>
                    </label>
                    <select id="type"
                            name="type"
                            required
                            x-model="selectedType"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-300 bg-white @error('type') border-red-400 @enderror">
                        <option value="" disabled>Выберите тип</option>
                        @if(isset($types))
                            @foreach($types as $value => $label)
                                <option value="{{ $value }}" {{ old('type', $listing->type) === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        @else
                            <option value="sell_business"    {{ old('type', $listing->type) === 'sell_business'    ? 'selected' : '' }}>Продажа бизнеса</option>
                            <option value="buy_business"     {{ old('type', $listing->type) === 'buy_business'     ? 'selected' : '' }}>Покупка бизнеса</option>
                            <option value="seek_investment"  {{ old('type', $listing->type) === 'seek_investment'  ? 'selected' : '' }}>Поиск инвестиций</option>
                            <option value="offer_investment" {{ old('type', $listing->type) === 'offer_investment' ? 'selected' : '' }}>Предложение инвестиций</option>
                            <option value="franchise"        {{ old('type', $listing->type) === 'franchise'        ? 'selected' : '' }}>Франшиза</option>
                            <option value="partnership"      {{ old('type', $listing->type) === 'partnership'      ? 'selected' : '' }}>Поиск партнёра</option>
                            <option value="real_estate"      {{ old('type', $listing->type) === 'real_estate'      ? 'selected' : '' }}>Недвижимость</option>
                            <option value="equipment"        {{ old('type', $listing->type) === 'equipment'        ? 'selected' : '' }}>Оборудование</option>
                        @endif
                    </select>
                    @error('type')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Заголовок объявления <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           id="title"
                           name="title"
                           value="{{ old('title', $listing->title) }}"
                           required
                           maxlength="255"
                           placeholder="Например: Продаю кофейню в центре Минска"
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-300 @error('title') border-red-400 @enderror">
                    @error('title')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Category & Subcategory -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Категория <span class="text-red-500">*</span>
                        </label>
                        <select id="category_id"
                                name="category_id"
                                required
                                x-model="selectedCategory"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-300 bg-white @error('category_id') border-red-400 @enderror">
                            <option value="">Выберите категорию</option>
                            @if(isset($categories))
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id', $listing->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        @error('category_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="subcategory_id" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Подкатегория
                        </label>
                        <select id="subcategory_id"
                                name="subcategory_id"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-300 bg-white">
                            <option value="">Выберите подкатегорию</option>
                            @if(isset($subcategories))
                                @foreach($subcategories as $sub)
                                    <option value="{{ $sub->id }}"
                                        {{ old('subcategory_id', $listing->subcategory_id) == $sub->id ? 'selected' : '' }}>
                                        {{ $sub->name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                <!-- Location -->
                <div>
                    <label for="location" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Местоположение <span class="text-red-500">*</span>
                    </label>
                    <select id="location"
                            name="location"
                            required
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-300 bg-white @error('location') border-red-400 @enderror">
                        <option value="">Выберите город / регион</option>
                        @if(isset($locations))
                            @foreach($locations as $loc)
                                <option value="{{ $loc }}" {{ old('location', $listing->location) === $loc ? 'selected' : '' }}>{{ $loc }}</option>
                            @endforeach
                        @else
                            @foreach(['Минск', 'Брест', 'Витебск', 'Гомель', 'Гродно', 'Могилёв', 'Брестская область', 'Витебская область', 'Гомельская область', 'Гродненская область', 'Минская область', 'Могилёвская область'] as $loc)
                                <option value="{{ $loc }}" {{ old('location', $listing->location) === $loc ? 'selected' : '' }}>{{ $loc }}</option>
                            @endforeach
                        @endif
                    </select>
                    @error('location')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Описание <span class="text-red-500">*</span>
                    </label>
                    <textarea id="description"
                              name="description"
                              rows="6"
                              required
                              placeholder="Подробно опишите ваш бизнес..."
                              class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-300 resize-y @error('description') border-red-400 @enderror">{{ old('description', $listing->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Section 2: Price -->
        <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                <h2 class="font-semibold text-gray-900 flex items-center gap-2">
                    <span class="w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">2</span>
                    Стоимость
                </h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-1.5">Цена (от)</label>
                        <input type="number"
                               id="price"
                               name="price"
                               value="{{ old('price', $listing->price) }}"
                               min="0" step="1"
                               :disabled="priceNegotiable"
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-300 disabled:bg-gray-50 disabled:text-gray-400">
                    </div>
                    <div>
                        <label for="price_max" class="block text-sm font-medium text-gray-700 mb-1.5">Цена (до)</label>
                        <input type="number"
                               id="price_max"
                               name="price_max"
                               value="{{ old('price_max', $listing->price_max) }}"
                               min="0" step="1"
                               :disabled="priceNegotiable"
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-300 disabled:bg-gray-50 disabled:text-gray-400">
                    </div>
                    <div>
                        <label for="currency" class="block text-sm font-medium text-gray-700 mb-1.5">Валюта</label>
                        <select id="currency"
                                name="currency"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-300 bg-white">
                            @foreach(['BYN', 'USD', 'EUR', 'RUB'] as $cur)
                                <option value="{{ $cur }}" {{ old('currency', $listing->currency) === $cur ? 'selected' : '' }}>{{ $cur }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <label class="flex items-center gap-3 cursor-pointer select-none">
                    <input type="checkbox"
                           name="price_negotiable"
                           value="1"
                           x-model="priceNegotiable"
                           {{ old('price_negotiable', $listing->price_negotiable) ? 'checked' : '' }}
                           class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-300">
                    <span class="text-sm text-gray-700">Цена договорная</span>
                </label>
            </div>
        </div>

        <!-- Section 3: Financials -->
        <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                <h2 class="font-semibold text-gray-900 flex items-center gap-2">
                    <span class="w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">3</span>
                    Финансовые показатели
                </h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="monthly_revenue" class="block text-sm font-medium text-gray-700 mb-1.5">Выручка в месяц</label>
                        <div class="relative">
                            <input type="number" id="monthly_revenue" name="monthly_revenue"
                                   value="{{ old('monthly_revenue', $listing->monthly_revenue) }}"
                                   min="0" step="1" placeholder="0"
                                   class="w-full pl-4 pr-16 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm">BYN</span>
                        </div>
                    </div>
                    <div>
                        <label for="monthly_profit" class="block text-sm font-medium text-gray-700 mb-1.5">Прибыль в месяц</label>
                        <div class="relative">
                            <input type="number" id="monthly_profit" name="monthly_profit"
                                   value="{{ old('monthly_profit', $listing->monthly_profit) }}"
                                   min="0" step="1" placeholder="0"
                                   class="w-full pl-4 pr-16 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm">BYN</span>
                        </div>
                    </div>
                    <div>
                        <label for="payback_months" class="block text-sm font-medium text-gray-700 mb-1.5">Срок окупаемости</label>
                        <div class="relative">
                            <input type="number" id="payback_months" name="payback_months"
                                   value="{{ old('payback_months', $listing->payback_months) }}"
                                   min="1" max="360" step="1" placeholder="12"
                                   class="w-full pl-4 pr-20 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm">мес.</span>
                        </div>
                    </div>
                    <div>
                        <label for="investment_amount" class="block text-sm font-medium text-gray-700 mb-1.5">Сумма инвестиций</label>
                        <div class="relative">
                            <input type="number" id="investment_amount" name="investment_amount"
                                   value="{{ old('investment_amount', $listing->investment_amount) }}"
                                   min="0" step="1" placeholder="0"
                                   class="w-full pl-4 pr-16 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm">BYN</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 4: Business Details -->
        <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                <h2 class="font-semibold text-gray-900 flex items-center gap-2">
                    <span class="w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">4</span>
                    Детали бизнеса
                </h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="year_founded" class="block text-sm font-medium text-gray-700 mb-1.5">Год основания</label>
                        <input type="number" id="year_founded" name="year_founded"
                               value="{{ old('year_founded', $listing->year_founded) }}"
                               min="1900" max="{{ date('Y') }}" step="1"
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
                    </div>
                    <div>
                        <label for="employees_count" class="block text-sm font-medium text-gray-700 mb-1.5">Количество сотрудников</label>
                        <input type="number" id="employees_count" name="employees_count"
                               value="{{ old('employees_count', $listing->employees_count) }}"
                               min="0" step="1"
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
                    </div>
                    <div>
                        <label for="ownership_type" class="block text-sm font-medium text-gray-700 mb-1.5">Форма собственности</label>
                        <select id="ownership_type" name="ownership_type"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-300 bg-white">
                            <option value="">Не указано</option>
                            @foreach(['ИП', 'ООО', 'ОДО', 'АО', 'ЗАО', 'Другое'] as $ot)
                                <option value="{{ $ot }}" {{ old('ownership_type', $listing->ownership_type) === $ot ? 'selected' : '' }}>{{ $ot }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="sale_reason" class="block text-sm font-medium text-gray-700 mb-1.5">Причина продажи</label>
                        <input type="text" id="sale_reason" name="sale_reason"
                               value="{{ old('sale_reason', $listing->sale_reason) }}"
                               maxlength="255" placeholder="Переезд, смена деятельности..."
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 5: Images -->
        <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                <h2 class="font-semibold text-gray-900 flex items-center gap-2">
                    <span class="w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">5</span>
                    Фотографии
                </h2>
            </div>
            <div class="p-6">
                <!-- Existing Images -->
                @if($listing->main_image || ($listing->images && count($listing->images)))
                    <div class="mb-4">
                        <p class="text-sm font-medium text-gray-700 mb-3">Текущие фотографии</p>
                        <div class="flex flex-wrap gap-3">
                            @if($listing->main_image)
                                <div class="relative group">
                                    <img src="{{ asset('storage/' . $listing->main_image) }}"
                                         alt="Главное фото"
                                         class="w-24 h-20 object-cover rounded-lg border border-gray-200">
                                    <div class="absolute top-1 left-1 bg-blue-600 text-white text-xs px-1.5 py-0.5 rounded font-medium">
                                        Главное
                                    </div>
                                </div>
                            @endif
                            @if($listing->images && is_array($listing->images))
                                @foreach($listing->images as $img)
                                    <div class="relative group">
                                        <img src="{{ asset('storage/' . $img) }}"
                                             alt=""
                                             class="w-24 h-20 object-cover rounded-lg border border-gray-200">
                                        <label class="absolute top-1 right-1 cursor-pointer opacity-0 group-hover:opacity-100 transition-opacity">
                                            <input type="checkbox" name="delete_images[]" value="{{ $img }}" class="sr-only">
                                            <span class="flex items-center justify-center w-5 h-5 bg-red-500 text-white rounded-full text-xs font-bold">×</span>
                                        </label>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <p class="text-xs text-gray-400 mt-2">Наведите на фото и нажмите ×, чтобы отметить на удаление</p>
                    </div>
                @endif

                <!-- New Images Upload -->
                <div x-data="{ files: [] }">
                    <label class="block w-full cursor-pointer">
                        <div class="border-2 border-dashed border-gray-200 hover:border-blue-300 rounded-xl p-6 text-center transition-colors">
                            <svg class="w-8 h-8 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"/>
                            </svg>
                            <p class="text-sm font-medium text-gray-700">Добавить новые фотографии</p>
                            <p class="text-xs text-gray-400 mt-1">JPG, PNG, WEBP до 5 МБ каждый</p>
                        </div>
                        <input type="file"
                               name="images[]"
                               multiple
                               accept="image/jpeg,image/png,image/webp"
                               class="sr-only"
                               x-on:change="files = [...$event.target.files]">
                    </label>

                    <template x-if="files.length > 0">
                        <div class="mt-3 flex flex-wrap gap-2">
                            <template x-for="(file, i) in files" :key="i">
                                <div class="relative w-20 h-16 rounded-lg overflow-hidden bg-gray-100 border border-gray-200">
                                    <img :src="URL.createObjectURL(file)" class="w-full h-full object-cover">
                                </div>
                            </template>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- Submit -->
        <div class="flex items-center justify-between bg-white rounded-xl border border-gray-100 p-5">
            <div class="flex items-center gap-4">
                <a href="{{ route('my-listings.index') }}"
                   class="text-gray-500 hover:text-gray-700 text-sm font-medium transition-colors">
                    ← Назад
                </a>
                <a href="{{ route('listings.show', $listing->slug) }}"
                   target="_blank"
                   class="text-blue-600 hover:text-blue-700 text-sm font-medium transition-colors flex items-center gap-1">
                    Просмотр
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                </a>
            </div>
            <button type="submit"
                    class="px-8 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition-colors">
                Сохранить изменения
            </button>
        </div>
    </div>
</form>

@endsection

@push('scripts')
<script>
document.getElementById('category_id')?.addEventListener('change', function () {
    const categoryId = this.value;
    const subcategorySelect = document.getElementById('subcategory_id');
    if (!categoryId) {
        subcategorySelect.innerHTML = '<option value="">Выберите подкатегорию</option>';
        return;
    }
    fetch(`/api/subcategories?category_id=${categoryId}`)
        .then(r => r.json())
        .then(data => {
            subcategorySelect.innerHTML = '<option value="">Выберите подкатегорию</option>';
            data.forEach(sub => {
                const opt = document.createElement('option');
                opt.value = sub.id;
                opt.textContent = sub.name;
                if (sub.id == {{ $listing->subcategory_id ?? 'null' }}) {
                    opt.selected = true;
                }
                subcategorySelect.appendChild(opt);
            });
        })
        .catch(() => {});
});
</script>
@endpush

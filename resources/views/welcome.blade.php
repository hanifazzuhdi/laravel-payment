<x-app-layout>
    <div class="container mt-5 py-10 bg-white">

        <h3 class="text-3xl mb-8 font-bold text-center">Form Donasi</h3>

        <form action="#" id="donation_form">
            <div class="grid sm:grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <x-label for="donor_name" :value="__('Nama Donatur')" />

                    <x-input id="donor_name" class="block mt-2 w-full" type="text" name="donor_name"
                        :value="old('donor_name')" required />
                </div>
                <div>
                    <x-label for="donor_email" :value="__('Email Donatur')" />

                    <x-input id="donor_email" class="block mt-2 w-full" type="text" name="donor_email"
                        :value="old('donor_email')" required />
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <x-label for="donation_type" :value="__('Tipe Donation')" />

                    <select
                        class="mt-2 form-select w-full px-3 py-2 mb-1 border-2 rounded border-gray-200 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition-colors cursor-pointer"
                        name="donation_type" id="donation_type">
                        <option value="kemanusiaan">Kemanusiaan</option>
                        <option value="bencana">Bencana</option>
                        <option value="pendidikan">Pendidikan</option>
                        <option value="santri">Santri</option>
                    </select>
                </div>

                <div>
                    <x-label for="amount" :value="__('Jumlah Donasi')" />

                    <x-input id="amount" class="block mt-2 w-full" type="text" name="amount" :value="old('amount')"
                        required />
                </div>
            </div>

            <div class="mb-4">
                <x-label for="note" :value="__('Catatan (Opsional)')" />

                <textarea
                    class="mt-2 form-select w-full px-3 py-2 mb-1 border-2 border-gray-200 rounded focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition-colors"
                    name="note" id="note" rows="5"></textarea>
            </div>

            <div class="flex justify-end">
                <x-button class="bg-blue-500 hover:bg-blue-600" id="pay-button">Donasi</x-button>
            </div>

            @csrf
        </form>
    </div>
</x-app-layout>

<footer class="bg-gray-100 py-8 mt-12">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div>
                <div class="flex items-center mb-4">
                    <img src="{{ asset('logo.png') }}" alt="Logo" class="h-10" />
                </div>
                <p class="text-gray-600 mb-4">
                    {{ $appset->deskripsi }}
                </p>
                <p class="text-gray-500">© {{ date('Y') }} {{ $appset->name }}. All rights reserved.</p>
            </div>
            <div>
                <h3 class="font-bold mb-4">Menu Utama</h3>
                <ul class="space-y-2">
                    <li><a href="#" class="text-gray-600 hover:text-black">Beranda</a></li>
                    <li><a href="#" class="text-gray-600 hover:text-black">Acara</a></li>
                    <li><a href="#" class="text-gray-600 hover:text-black">Layanan</a></li>
                </ul>
            </div>
            <div>
                <h3 class="font-bold mb-4">Hubungi Kami</h3>
                <div class="space-y-4">
                    <div class="flex items-center">
                        <div
                            class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center text-black mr-3">
                            <i class="fab fa-whatsapp text-xl"></i>
                        </div>
                        <div>
                            <a href="https://wa.me/{{ $appset->whatsapp }}"
                                class="text-gray-600 hover:text-black">{{ $appset->whatsapp }}</a>
                        </div>
                    </div>

                    <div class="flex items-center">
                        <div
                            class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center text-black mr-3">
                            <i class="fab fa-instagram text-xl"></i>
                        </div>
                        <div>
                            <a href="https://instagram.com/{{ $appset->instagram }}"
                                class="text-gray-600 hover:text-black">{{ $appset->instagram }}</a>
                        </div>
                    </div>

                    <div class="flex items-center">
                        <div
                            class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center text-black mr-3">
                            <i class="far fa-envelope text-xl"></i>
                        </div>
                        <div>
                            <a href="mailto:{{ $appset->email }}"
                                class="text-gray-600 hover:text-black">{{ $appset->email }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

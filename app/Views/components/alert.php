<div x-data="{ show: false, message: '', type: 'success' }" 
     x-show="show"
     x-init="
        $watch('show', value => {
            if(value) setTimeout(() => show = false, 3000)
        })"
     :class="{
        'bg-green-100 text-green-800': type === 'success',
        'bg-red-100 text-red-800': type === 'error'
     }"
     class="fixed top-4 right-4 px-4 py-2 rounded-lg shadow-lg">
    <p x-text="message"></p>
</div>

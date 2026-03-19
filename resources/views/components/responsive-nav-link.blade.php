@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-secondary text-start text-base font-medium text-primary bg-light focus:outline-none focus:text-secondary focus:bg-accent focus:border-accent transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-600 hover:text-primary hover:bg-light hover:border-secondary focus:outline-none focus:text-primary focus:bg-light focus:border-secondary transition duration-150 ease-in-out';
</a>

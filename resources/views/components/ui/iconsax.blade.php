@props([
    'name',
    'class' => 'h-4 w-4 shrink-0',
    'variant' => 'linear',
])

@php
    $attrs = $attributes->merge([
        'class' => $class,
        'xmlns' => 'http://www.w3.org/2000/svg',
        'viewBox' => '0 0 24 24',
        'fill' => 'none',
        'aria-hidden' => 'true',
    ]);
@endphp

@switch($name)
    @case('layout-dashboard')
        <svg {{ $attrs }}>
        <path d="M5 10h2c2 0 3-1 3-3V5c0-2-1-3-3-3H5C3 2 2 3 2 5v2c0 2 1 3 3 3ZM17 10h2c2 0 3-1 3-3V5c0-2-1-3-3-3h-2c-2 0-3 1-3 3v2c0 2 1 3 3 3ZM17 22h2c2 0 3-1 3-3v-2c0-2-1-3-3-3h-2c-2 0-3 1-3 3v2c0 2 1 3 3 3ZM5 22h2c2 0 3-1 3-3v-2c0-2-1-3-3-3H5c-2 0-3 1-3 3v2c0 2 1 3 3 3Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" />
        </svg>
        @break
    @case('library')
        <svg {{ $attrs }}>
        <path d="M22 16.74V4.67c0-1.2-.98-2.09-2.17-1.99h-.06c-2.1.18-5.29 1.25-7.07 2.37l-.17.11c-.29.18-.77.18-1.06 0l-.25-.15C9.44 3.9 6.26 2.84 4.16 2.67 2.97 2.57 2 3.47 2 4.66v12.08c0 .96.78 1.86 1.74 1.98l.29.04c2.17.29 5.52 1.39 7.44 2.44l.04.02c.27.15.7.15.96 0 1.92-1.06 5.28-2.17 7.46-2.46l.33-.04c.96-.12 1.74-1.02 1.74-1.98ZM12 5.49v15M7.75 8.49H5.5M8.5 11.49h-3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        @break
    @case('book-open')
        <svg {{ $attrs }}>
        <path d="M3.5 18V7c0-4 1-5 5-5h7c4 0 5 1 5 5v10c0 .14 0 .28-.01.42" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
        <path d="M6.35 15H20.5v3.5c0 1.93-1.57 3.5-3.5 3.5H7c-1.93 0-3.5-1.57-3.5-3.5v-.65C3.5 16.28 4.78 15 6.35 15ZM8 7h8M8 10.5h5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        @break
    @case('receipt-text')
        <svg {{ $attrs }}>
        <path d="M22 6v2.42C22 10 21 11 19.42 11H16V4.01C16 2.9 16.91 2 18.02 2c1.09.01 2.09.45 2.81 1.17C21.55 3.9 22 4.9 22 6Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" />
        <path d="M2 7v14c0 .83.94 1.3 1.6.8l1.71-1.28c.4-.3.96-.26 1.32.1l1.66 1.67c.39.39 1.03.39 1.42 0l1.68-1.68c.35-.35.91-.39 1.3-.09l1.71 1.28c.66.49 1.6.02 1.6-.8V4c0-1.1.9-2 2-2H6C3 2 2 3.79 2 6v1Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" />
        <path d="M6 9h6M6.75 13h4.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        @break
    @case('folder')
        <svg {{ $attrs }}>
        <path d="M22 11v6c0 4-1 5-5 5H7c-4 0-5-1-5-5V7c0-4 1-5 5-5h1.5c1.5 0 1.83.44 2.4 1.2l1.5 2c.38.5.6.8 1.6.8h3c4 0 5 1 5 5Z" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" />
        </svg>
        @break
    @case('layers')
        <svg {{ $attrs }}>
        <path d="M13.01 2.92l5.9 2.62c1.7.75 1.7 1.99 0 2.74l-5.9 2.62c-.67.3-1.77.3-2.44 0l-5.9-2.62c-1.7-.75-1.7-1.99 0-2.74l5.9-2.62c.67-.3 1.77-.3 2.44 0z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
        <path d="M3 11c0 .84.63 1.81 1.4 2.15l6.79 3.02c.52.23 1.11.23 1.62 0l6.79-3.02c.77-.34 1.4-1.31 1.4-2.15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
        <path d="M3 16c0 .93.55 1.77 1.4 2.15l6.79 3.02c.52.23 1.11.23 1.62 0l6.79-3.02c.85-.38 1.4-1.22 1.4-2.15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        @break
    @case('chevron-down')
        <svg {{ $attrs }}>
        <path d="M19.92 8.95l-6.52 6.52c-.77.77-2.03.77-2.8 0L4.08 8.95" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" />
        </svg>
        @break
    @case('file-text')
        <svg {{ $attrs }}>
        <path d="M21 7v10c0 3-1.5 5-5 5H8c-3.5 0-5-2-5-5V7c0-3 1.5-5 5-5h8c3.5 0 5 2 5 5Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" />
        <path d="M14.5 4.5v2c0 1.1.9 2 2 2h2M8 13h4M8 17h8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" />
        </svg>
        @break
    @case('video')
        <svg {{ $attrs }}>
        <path d="M22 15V9c0-5-2-7-7-7H9C4 2 2 4 2 9v6c0 5 2 7 7 7h6c5 0 7-2 7-7ZM2.52 7.11h18.96M8.52 2.11v4.86M15.48 2.11v4.41" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
        <path d="M9.75 14.45v-1.2c0-1.54 1.09-2.17 2.42-1.4l1.04.6 1.04.6c1.33.77 1.33 2.03 0 2.8l-1.04.6-1.04.6c-1.33.77-2.42.14-2.42-1.4v-1.2 0Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" />
        </svg>
        @break
    @case('calendar')
        <svg {{ $attrs }}>
        <path d="M8 2v3M16 2v3M3.5 9.09h17M21 8.5V17c0 3-1.5 5-5 5H8c-3.5 0-5-2-5-5V8.5c0-3 1.5-5 5-5h8c3.5 0 5 2 5 5Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" />
        <path d="M15.695 13.7h.009M15.695 16.7h.009M11.995 13.7h.01M11.995 16.7h.01M8.294 13.7h.01M8.294 16.7h.01" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        @break
    @case('circle-dollar-sign')
        <svg {{ $attrs }}>
        <path d="M8.672 14.33c0 1.29.99 2.33 2.22 2.33h2.51c1.07 0 1.94-.91 1.94-2.03 0-1.22-.53-1.65-1.32-1.93l-4.03-1.4c-.79-.28-1.32-.71-1.32-1.93 0-1.12.87-2.03 1.94-2.03h2.51c1.23 0 2.22 1.04 2.22 2.33M12 6v12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
        <path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        @break
    @case('award')
        <svg {{ $attrs }}>
        <path d="M4.26 11.02v4.97c0 1.82 0 1.82 1.72 2.98l4.73 2.73c.71.41 1.87.41 2.58 0l4.73-2.73c1.72-1.16 1.72-1.16 1.72-2.98v-4.97c0-1.82 0-1.82-1.72-2.98l-4.73-2.73c-.71-.41-1.87-.41-2.58 0L5.98 8.04C4.26 9.2 4.26 9.2 4.26 11.02Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
        <path d="M17.5 7.63V5c0-2-1-3-3-3h-5c-2 0-3 1-3 3v2.56M12.63 10.99l.57.89c.09.14.29.28.44.32l1.02.26c.63.16.8.7.39 1.2l-.67.81c-.1.13-.18.36-.17.52l.06 1.05c.04.65-.42.98-1.02.74l-.98-.39a.863.863 0 0 0-.55 0l-.98.39c-.6.24-1.06-.1-1.02-.74l.06-1.05c.01-.16-.07-.4-.17-.52l-.67-.81c-.41-.5-.24-1.04.39-1.2l1.02-.26c.16-.04.36-.19.44-.32l.57-.89c.36-.54.92-.54 1.27 0Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        @break
    @case('log-out')
        <svg {{ $attrs }}>
        <path d="M17.44 14.62L20 12.06 17.44 9.5M9.76 12.06h10.17M11.76 20c-4.42 0-8-3-8-8s3.58-8 8-8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" />
        </svg>
        @break
    @case('menu')
        <svg {{ $attrs }}>
        <path d="M17.54 8.31a2.46 2.46 0 1 0 0-4.92 2.46 2.46 0 0 0 0 4.92ZM6.46 8.31a2.46 2.46 0 1 0 0-4.92 2.46 2.46 0 0 0 0 4.92ZM17.54 20.61a2.46 2.46 0 1 0 0-4.92 2.46 2.46 0 0 0 0 4.92ZM6.46 20.61a2.46 2.46 0 1 0 0-4.92 2.46 2.46 0 0 0 0 4.92Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" />
        </svg>
        @break
    @case('x')
        <svg {{ $attrs }}>
        <path d="M12 22c5.5 0 10-4.5 10-10S17.5 2 12 2 2 6.5 2 12s4.5 10 10 10ZM9.17 14.83l5.66-5.66M14.83 14.83 9.17 9.17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        @break
    @case('user')
        <svg {{ $attrs }}>
        <path d="M12 12a5 5 0 1 0 0-10 5 5 0 0 0 0 10ZM20.59 22c0-3.87-3.85-7-8.59-7s-8.59 3.13-8.59 7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        @break
    @case('home')
        <svg {{ $attrs }}>
        <path d="M12 18v-3M10.07 2.82 3.14 8.37c-.78.62-1.28 1.93-1.11 2.91l1.33 7.96c.24 1.42 1.6 2.57 3.04 2.57h11.2c1.43 0 2.8-1.16 3.04-2.57l1.33-7.96c.16-.98-.34-2.29-1.11-2.91l-6.93-5.54c-1.07-.86-2.8-.86-3.86-.01Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        @break
    @case('credit-card')
        <svg {{ $attrs }}>
        <path d="M2 8.505h20M6 16.505h2M10.5 16.505h4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" />
        <path d="M6.44 3.505h11.11c3.56 0 4.45.88 4.45 4.39v8.21c0 3.51-.89 4.39-4.44 4.39H6.44c-3.55.01-4.44-.87-4.44-4.38v-8.22c0-3.51.89-4.39 4.44-4.39Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        @break
    @case('list')
        <svg {{ $attrs }}>
        <path d="m21.93 6.76-3.37 13.53A2.228 2.228 0 0 1 16.38 22H3.24c-1.51 0-2.59-1.48-2.14-2.93L5.31 5.55a2.25 2.25 0 0 1 2.14-1.59h12.3c.95 0 1.74.58 2.07 1.38.19.43.23.92.11 1.42Z" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" />
        <path d="M16 22h4.78c1.29 0 2.3-1.09 2.21-2.38L22 6M9.68 6.38l1.04-4.32M16.38 6.39l.94-4.34M7.7 12h8M6.7 16h8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" />
        </svg>
        @break
    @case('users')
        <svg {{ $attrs }}>
        <path d="M18 7.16a.605.605 0 0 0-.19 0 2.573 2.573 0 0 1-2.48-2.58c0-1.43 1.15-2.58 2.58-2.58a2.58 2.58 0 0 1 2.58 2.58A2.589 2.589 0 0 1 18 7.16ZM16.97 14.44c1.37.23 2.88-.01 3.94-.72 1.41-.94 1.41-2.48 0-3.42-1.07-.71-2.6-.95-3.97-.71M5.97 7.16c.06-.01.13-.01.19 0a2.573 2.573 0 0 0 2.48-2.58C8.64 3.15 7.49 2 6.06 2a2.58 2.58 0 0 0-2.58 2.58c.01 1.4 1.11 2.53 2.49 2.58ZM7 14.44c-1.37.23-2.88-.01-3.94-.72-1.41-.94-1.41-2.48 0-3.42 1.07-.71 2.6-.95 3.97-.71M12 14.63a.605.605 0 0 0-.19 0 2.573 2.573 0 0 1-2.48-2.58c0-1.43 1.15-2.58 2.58-2.58a2.58 2.58 0 0 1 2.58 2.58c-.01 1.4-1.11 2.54-2.49 2.58ZM9.09 17.78c-1.41.94-1.41 2.48 0 3.42 1.6 1.07 4.22 1.07 5.82 0 1.41-.94 1.41-2.48 0-3.42-1.59-1.06-4.22-1.06-5.82 0Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        @break
    @case('shield-check')
        <svg {{ $attrs }}>
        <path d="M10.49 2.23 5.5 4.11c-1.15.43-2.09 1.79-2.09 3.01v7.43c0 1.18.78 2.73 1.73 3.44l4.3 3.21c1.41 1.06 3.73 1.06 5.14 0l4.3-3.21c.95-.71 1.73-2.26 1.73-3.44V7.12c0-1.23-.94-2.59-2.09-3.02l-4.99-1.87c-.85-.31-2.21-.31-3.04 0Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
        <path d="m9.05 11.87 1.61 1.61 4.3-4.3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        @break
    @case('user-check')
        <svg {{ $attrs }}>
        <path d="M12 12a5 5 0 1 0 0-10 5 5 0 0 0 0 10ZM3.41 22c0-3.87 3.85-7 8.59-7 .96 0 1.89.13 2.76.37" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
        <path d="M22 18c0 .75-.21 1.46-.58 2.06-.21.36-.48.68-.79.94-.7.63-1.62 1-2.63 1a3.97 3.97 0 0 1-3.42-1.94A3.92 3.92 0 0 1 14 18c0-1.26.58-2.39 1.5-3.12A3.999 3.999 0 0 1 22 18Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" />
        <path d="m16.44 18 .99.99 2.13-1.97" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        @break
    @case('users-round')
        <svg {{ $attrs }}>
        <path d="M9.16 10.87c-.1-.01-.22-.01-.33 0a4.42 4.42 0 0 1-4.27-4.43C4.56 3.99 6.54 2 9 2a4.435 4.435 0 0 1 .16 8.87ZM16.41 4c1.94 0 3.5 1.57 3.5 3.5 0 1.89-1.5 3.43-3.37 3.5a1.13 1.13 0 0 0-.26 0M4.16 14.56c-2.42 1.62-2.42 4.26 0 5.87 2.75 1.84 7.26 1.84 10.01 0 2.42-1.62 2.42-4.26 0-5.87-2.74-1.83-7.25-1.83-10.01 0ZM18.34 20c.72-.15 1.4-.44 1.96-.87 1.56-1.17 1.56-3.1 0-4.27-.55-.42-1.22-.7-1.93-.86" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        @break
    @case('clipboard-check')
        <svg {{ $attrs }}>
        <path d="m9.31 14.7 1.5 1.5 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
        <path d="M10 6h4c2 0 2-1 2-2 0-2-1-2-2-2h-4C9 2 8 2 8 4s1 2 2 2Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" />
        <path d="M16 4.02c3.33.18 5 1.41 5 5.98v6c0 4-1 6-6 6H9c-5 0-6-2-6-6v-6c0-4.56 1.67-5.8 5-5.98" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" />
        </svg>
        @break
    @case('badge-check')
        <svg {{ $attrs }}>
        <path d="m8.38 12 2.41 2.42 4.83-4.84" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
        <path d="M10.75 2.45c.69-.59 1.82-.59 2.52 0l1.58 1.36c.3.26.86.47 1.26.47h1.7c1.06 0 1.93.87 1.93 1.93v1.7c0 .39.21.96.47 1.26l1.36 1.58c.59.69.59 1.82 0 2.52l-1.36 1.58c-.26.3-.47.86-.47 1.26v1.7c0 1.06-.87 1.93-1.93 1.93h-1.7c-.39 0-.96.21-1.26.47l-1.58 1.36c-.69.59-1.82.59-2.52 0l-1.58-1.36c-.3-.26-.86-.47-1.26-.47H6.18c-1.06 0-1.93-.87-1.93-1.93V16.1c0-.39-.21-.95-.46-1.25l-1.35-1.59c-.58-.69-.58-1.81 0-2.5l1.35-1.59c.25-.3.46-.86.46-1.25V6.2c0-1.06.87-1.93 1.93-1.93h1.73c.39 0 .96-.21 1.26-.47l1.58-1.35Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        @break
    @case('message-square')
        <svg {{ $attrs }}>
        <path d="M18.28 9.76v3.14c0 .2-.01.4-.03.59-.18 2.12-1.43 3.18-3.73 3.18h-.31c-.2 0-.39.09-.5.25l-.94 1.26c-.42.56-1.09.56-1.51 0l-.94-1.26a.733.733 0 0 0-.5-.25h-.31c-2.51 0-3.76-.62-3.76-3.76V9.77c0-2.3 1.06-3.55 3.18-3.73.19-.02.39-.03.59-.03h5.03c2.47-.01 3.73 1.25 3.73 3.75Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" />
        <path d="M9 22h6c5 0 7-2 7-7V9c0-5-2-7-7-7H9C4 2 2 4 2 9v6c0 5 2 7 7 7Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        @break
    @case('settings')
        <svg {{ $attrs }}>
        <path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" />
        <path d="M2 12.88v-1.76c0-1.04.85-1.9 1.9-1.9 1.81 0 2.55-1.28 1.64-2.85-.52-.9-.21-2.07.7-2.59l1.73-.99c.79-.47 1.81-.19 2.28.6l.11.19c.9 1.57 2.38 1.57 3.29 0l.11-.19c.47-.79 1.49-1.07 2.28-.6l1.73.99c.91.52 1.22 1.69.7 2.59-.91 1.57-.17 2.85 1.64 2.85 1.04 0 1.9.85 1.9 1.9v1.76c0 1.04-.85 1.9-1.9 1.9-1.81 0-2.55 1.28-1.64 2.85.52.91.21 2.07-.7 2.59l-1.73.99c-.79.47-1.81.19-2.28-.6l-.11-.19c-.9-1.57-2.38-1.57-3.29 0l-.11.19c-.47.79-1.49 1.07-2.28.6l-1.73-.99a1.899 1.899 0 0 1-.7-2.59c.91-1.57.17-2.85-1.64-2.85-1.05 0-1.9-.86-1.9-1.9Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" />
        </svg>
        @break
    @case('search')
        <svg {{ $attrs }}>
        <path d="M11 20a9 9 0 1 0 0-18 9 9 0 0 0 0 18ZM18.93 20.69c.53 1.6 1.74 1.76 2.67.36.85-1.28.29-2.33-1.25-2.33-1.14-.01-1.78.88-1.42 1.97Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        @break
    @case('bell')
        <svg {{ $attrs }}>
        <path d="M12.02 2.91c-3.31 0-6 2.69-6 6v2.89c0 .61-.26 1.54-.57 2.06L4.3 15.77c-.71 1.18-.22 2.49 1.08 2.93 4.31 1.44 8.96 1.44 13.27 0 1.21-.4 1.74-1.83 1.08-2.93l-1.15-1.91c-.3-.52-.56-1.45-.56-2.06V8.91c0-3.3-2.7-6-6-6Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-miterlimit="10" />
        <path d="M13.87 3.2a6.754 6.754 0 0 0-3.7 0c.29-.74 1.01-1.26 1.85-1.26.84 0 1.56.52 1.85 1.26Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" />
        <path d="M15.02 19.06c0 1.65-1.35 3-3 3-.82 0-1.58-.34-2.12-.88a3.01 3.01 0 0 1-.88-2.12" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" />
        </svg>
        @break
    @case('arrow-left')
        <svg {{ $attrs }}>
        <path d="M9.57 5.93L3.5 12l6.07 6.07M20.5 12H3.67" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" />
        </svg>
        @break
    @case('arrow-right')
        <svg {{ $attrs }}>
        <path d="M14.43 5.93L20.5 12l-6.07 6.07M3.5 12h16.83" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" />
        </svg>
        @break
    @case('inbox')
        <svg {{ $attrs }}>
        <path d="M12 2v7l2-2M12 9l-2-2M1.98 13h4.41c.38 0 .72.21.89.55l1.17 2.34A2 2 0 0 0 10.24 17h3.53a2 2 0 0 0 1.79-1.11l1.17-2.34a1 1 0 0 1 .89-.55h4.36" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
        <path d="M7 4.13c-3.54.52-5 2.6-5 6.87v4c0 5 2 7 7 7h6c5 0 7-2 7-7v-4c0-4.27-1.46-6.35-5-6.87" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        @break
    @case('calendar-x')
        <svg {{ $attrs }}>
        <path d="M8 2v3M16 2v3M3.5 9.09h17M18 23a4 4 0 1 0 0-8 4 4 0 0 0 0 8ZM19.07 20.11 16.95 18M19.05 18.02l-2.12 2.12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" />
        <path d="M21 8.5v7.86c-.73-.83-1.8-1.36-3-1.36-2.21 0-4 1.79-4 4 0 .75.21 1.46.58 2.06.21.36.48.68.79.94H8c-3.5 0-5-2-5-5V8.5c0-3 1.5-5 5-5h8c3.5 0 5 2 5 5Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" />
        <path d="M11.995 13.7h.01M8.294 13.7h.01M8.294 16.7h.01" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        @break
    @case('lightbulb')
        <svg {{ $attrs }}>
        <path d="M8.3 18.04v-1.16C6 15.49 4.11 12.78 4.11 9.9c0-4.95 4.55-8.83 9.69-7.71 2.26.5 4.24 2 5.27 4.07 2.09 4.2-.11 8.66-3.34 10.61v1.16c0 .29.11.96-.96.96H9.26c-1.1.01-.96-.42-.96-.95ZM8.5 22c2.29-.65 4.71-.65 7 0" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        @break
    @case('plus')
        <svg {{ $attrs }}>
        <path d="M6 12h12M12 18V6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        @break
    @case('download')
        <svg {{ $attrs }}>
        <path d="M9 11v6l2-2M9 17l-2-2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
        <path d="M22 10v5c0 5-2 7-7 7H9c-5 0-7-2-7-7V9c0-5 2-7 7-7h5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
        <path d="M22 10h-4c-3 0-4-1-4-4V2l8 8Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        @break
    @case('upload')
        <svg {{ $attrs }}>
        <path d="M9 17v-6l-2 2M9 11l2 2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
        <path d="M22 10v5c0 5-2 7-7 7H9c-5 0-7-2-7-7V9c0-5 2-7 7-7h5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
        <path d="M22 10h-4c-3 0-4-1-4-4V2l8 8Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        @break
    @case('play')
        <svg {{ $attrs }}>
        <path d="M4 12V8.44c0-4.42 3.13-6.23 6.96-4.02l3.09 1.78 3.09 1.78c3.83 2.21 3.83 5.83 0 8.04l-3.09 1.78-3.09 1.78C7.13 21.79 4 19.98 4 15.56V12Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" />
        </svg>
        @break
    @case('clock')
        <svg {{ $attrs }}>
        <path d="M22 12c0 5.52-4.48 10-10 10S2 17.52 2 12 6.48 2 12 2s10 4.48 10 10Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
        <path d="m15.71 15.18-3.1-1.85c-.54-.32-.98-1.09-.98-1.72v-4.1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        @break
    @case('star')
        <svg {{ $attrs }}>
        <path d="m15.39 5.21 1.41 2.82c.19.39.7.76 1.13.84l2.55.42c1.63.27 2.01 1.45.84 2.63l-1.99 1.99c-.33.33-.52.98-.41 1.45l.57 2.46c.45 1.94-.59 2.7-2.3 1.68l-2.39-1.42c-.43-.26-1.15-.26-1.58 0l-2.39 1.42c-1.71 1.01-2.75.26-2.3-1.68l.57-2.46c.11-.46-.08-1.11-.41-1.45L6.7 11.92c-1.17-1.17-.79-2.35.84-2.63l2.55-.42c.43-.07.94-.45 1.13-.84l1.41-2.82c.75-1.53 1.99-1.53 2.76 0ZM8 5H2M5 19H2M3 12H2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        @break
    @case('trash')
        <svg {{ $attrs }}>
        <path d="M21 5.98c-3.33-.33-6.68-.5-10.02-.5-1.98 0-3.96.1-5.94.3L3 5.98M8.5 4.97l.22-1.31C8.88 2.71 9 2 10.69 2h2.62c1.69 0 1.82.75 1.97 1.67l.22 1.3M18.85 9.14l-.65 10.07C18.09 20.78 18 22 15.21 22H8.79C6 22 5.91 20.78 5.8 19.21L5.15 9.14M10.33 16.5h3.33M9.5 12.5h5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        @break
    @case('edit')
        <svg {{ $attrs }}>
        <path d="M11 2H9C4 2 2 4 2 9v6c0 5 2 7 7 7h6c5 0 7-2 7-7v-2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
        <path d="M16.04 3.02 8.16 10.9c-.3.3-.6.89-.66 1.32l-.43 3.01c-.16 1.09.61 1.85 1.7 1.7l3.01-.43c.42-.06 1.01-.36 1.32-.66l7.88-7.88c1.36-1.36 2-2.94 0-4.94-2-2-3.58-1.36-4.94 0Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" />
        <path d="M14.91 4.15a7.144 7.144 0 0 0 4.94 4.94" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" />
        </svg>
        @break
    @case('eye')
        <svg {{ $attrs }}>
        <path d="M15.58 12c0 1.98-1.6 3.58-3.58 3.58S8.42 13.98 8.42 12s1.6-3.58 3.58-3.58 3.58 1.6 3.58 3.58Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
        <path d="M12 20.27c3.53 0 6.82-2.08 9.11-5.68.9-1.41.9-3.78 0-5.19-2.29-3.6-5.58-5.68-9.11-5.68-3.53 0-6.82 2.08-9.11 5.68-.9 1.41-.9 3.78 0 5.19 2.29 3.6 5.58 5.68 9.11 5.68Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        @break
    @default
        <svg {{ $attrs }}>
        <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.5" />
        </svg>
@endswitch

import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const root = path.join(__dirname, '..');
const iconsDir = path.join(root, 'node_modules/iconsax-reactjs/dist/esm');
const outFile = path.join(root, 'resources/views/components/ui/iconsax.blade.php');

const mapping = {
    'layout-dashboard': 'Category',
    library: 'Book1',
    'book-open': 'Book',
    'receipt-text': 'ReceiptText',
    folder: 'Folder',
    layers: 'Layer',
    'chevron-down': 'ArrowDown2',
    'file-text': 'DocumentText',
    video: 'VideoPlay',
    calendar: 'Calendar',
    'circle-dollar-sign': 'DollarCircle',
    award: 'Award',
    'log-out': 'Logout',
    menu: 'Menu',
    x: 'CloseCircle',
    user: 'User',
    home: 'Home',
    'credit-card': 'Card',
    list: 'MenuBoard',
    users: 'People',
    'shield-check': 'ShieldTick',
    'user-check': 'UserTick',
    'users-round': 'Profile2User',
    'clipboard-check': 'ClipboardTick',
    'badge-check': 'Verify',
    'message-square': 'MessageSquare',
    settings: 'Setting2',
    search: 'SearchNormal',
    bell: 'Notification',
    'arrow-left': 'ArrowLeft',
    'arrow-right': 'ArrowRight',
    inbox: 'DirectInbox',
    'calendar-x': 'CalendarRemove',
    lightbulb: 'LampOn',
    plus: 'Add',
    download: 'DocumentDownload',
    upload: 'DocumentUpload',
    play: 'Play',
    clock: 'Clock',
    star: 'Star',
    trash: 'Trash',
    edit: 'Edit',
    eye: 'Eye',
};

function extractLinearPaths(iconName) {
    const filePath = path.join(iconsDir, `${iconName}.js`);
    if (!fs.existsSync(filePath)) {
        console.warn(`Missing icon file: ${iconName}`);
        return [];
    }

    const content = fs.readFileSync(filePath, 'utf8');
    const start = content.indexOf('var Linear = function');
    const end = content.indexOf('var Outline = function', start);
    if (start === -1 || end === -1) return [];

    const block = content.slice(start, end);
    const paths = [];
    const regex = /React\.createElement\("path",\s*\{([\s\S]*?)\}\)/g;
    let match;

    while ((match = regex.exec(block)) !== null) {
        const raw = match[1];
        const props = {};
        const dMatch = raw.match(/\bd:\s*"([^"]*)"/);
        const opacityMatch = raw.match(/opacity:\s*"([^"]*)"/);
        const fillMatch = raw.match(/\bfill:\s*color/);
        const strokeMatch = raw.match(/\bstroke:\s*color/);
        const strokeWidthMatch = raw.match(/strokeWidth:\s*"([^"]*)"/);
        const strokeLinecapMatch = raw.match(/strokeLinecap:\s*"([^"]*)"/);
        const strokeLinejoinMatch = raw.match(/strokeLinejoin:\s*"([^"]*)"/);
        const strokeMiterlimitMatch = raw.match(/strokeMiterlimit:\s*"([^"]*)"/);

        if (dMatch) props.d = dMatch[1];
        if (opacityMatch) props.opacity = opacityMatch[1];
        if (fillMatch) props.fill = true;
        if (strokeMatch) props.stroke = true;
        if (strokeWidthMatch) props.strokeWidth = strokeWidthMatch[1];
        if (strokeLinecapMatch) props.strokeLinecap = strokeLinecapMatch[1];
        if (strokeLinejoinMatch) props.strokeLinejoin = strokeLinejoinMatch[1];
        if (strokeMiterlimitMatch) props.strokeMiterlimit = strokeMiterlimitMatch[1];

        if (props.d) paths.push(props);
    }

    return paths;
}

function renderPath(props) {
    const attrs = [];
    if (props.d) attrs.push(`d="${props.d}"`);
    if (props.opacity) attrs.push(`opacity="${props.opacity}"`);
    if (props.fill) attrs.push('fill="currentColor"');
    if (props.stroke) attrs.push('stroke="currentColor"');
    if (props.strokeWidth) attrs.push(`stroke-width="${props.strokeWidth}"`);
    if (props.strokeLinecap) attrs.push(`stroke-linecap="${props.strokeLinecap}"`);
    if (props.strokeLinejoin) attrs.push(`stroke-linejoin="${props.strokeLinejoin}"`);
    if (props.strokeMiterlimit) attrs.push(`stroke-miterlimit="${props.strokeMiterlimit}"`);
    return `        <path ${attrs.join(' ')} />`;
}

let blade = `@props([
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
`;

for (const [alias, iconName] of Object.entries(mapping)) {
    const paths = extractLinearPaths(iconName);
    blade += `    @case('${alias}')\n        <svg {{ $attrs }}>\n`;
    if (paths.length === 0) {
        blade += `        <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.5" />\n`;
        console.warn(`No paths for ${alias} -> ${iconName}`);
    } else {
        blade += paths.map(renderPath).join('\n') + '\n';
    }
    blade += `        </svg>\n        @break\n`;
}

blade += `    @default
        <svg {{ $attrs }}>
        <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.5" />
        </svg>
@endswitch
`;

fs.writeFileSync(outFile, blade);
console.log(`Generated ${outFile} with ${Object.keys(mapping).length} icons.`);

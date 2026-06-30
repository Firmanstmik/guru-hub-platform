#!/usr/bin/env node
/**
 * Regenerate default avatar AVIF/WebP from source PNGs in assets/.
 * Sources: default-*-source.png (generated or designed externally)
 */
import sharp from 'sharp';
import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const root = path.resolve(__dirname, '..');
const srcDir = path.resolve(root, '..', 'assets');
const outDir = path.join(root, 'public', 'assets', 'avatar');

const names = ['default-neutral', 'default-siswa-l', 'default-siswa-p', 'default-guru-l', 'default-guru-p'];

for (const name of names) {
    const input = path.join(srcDir, `${name}-source.png`);
    if (!fs.existsSync(input)) {
        console.warn(`skip: ${input} not found`);
        continue;
    }
    await sharp(input)
        .resize(512, 512, { fit: 'cover', position: 'centre' })
        .avif({ quality: 72 })
        .toFile(path.join(outDir, `${name}.avif`));
    await sharp(input)
        .resize(512, 512, { fit: 'cover', position: 'centre' })
        .webp({ quality: 82 })
        .toFile(path.join(outDir, `${name}.webp`));
    console.log('built', name);
}

import { readdir, readFile } from 'node:fs/promises';
import vm from 'node:vm';
import process from 'node:process';

const distDir = new URL('../dist/', import.meta.url);
const files = (await readdir(distDir)).filter((name) => name.endsWith('.js'));

if (files.length === 0) {
  console.error('validate-dist: no .js files found in dist/');
  process.exit(1);
}

let failed = false;

for (const file of files) {
  const source = await readFile(new URL(file, distDir), 'utf8');
  try {
    new vm.Script(source, { filename: file });
    console.log(`OK   ${file} (${Buffer.byteLength(source)} bytes)`);
  } catch (err) {
    failed = true;
    console.error(`FAIL ${file}: ${err.name}: ${err.message}`);
    const m = /<anonymous>:(\d+):(\d+)/.exec(String(err.stack));
    if (m) console.error(`     at line ${m[1]}, column ${m[2]}`);
  }
}

if (failed) {
  console.error('validate-dist: broken output found, refusing to ship');
  process.exit(1);
}

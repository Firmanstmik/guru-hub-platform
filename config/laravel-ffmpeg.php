<?php

// return [
//     'ffmpeg' => [
//         'binaries' => env('FFMPEG_BINARIES', 'ffmpeg'),

//         'threads' => 12,   // set to false to disable the default 'threads' filter
//     ],

//     'ffprobe' => [
//         'binaries' => env('FFPROBE_BINARIES', 'ffprobe'),
//     ],

//     'timeout' => 3600,

//     'log_channel' => env('LOG_CHANNEL', 'stack'),   // set to false to completely disable logging

//     'temporary_files_root' => env('FFMPEG_TEMPORARY_FILES_ROOT', sys_get_temp_dir()),

//     'temporary_files_encrypted_hls' => env('FFMPEG_TEMPORARY_ENCRYPTED_HLS', env('FFMPEG_TEMPORARY_FILES_ROOT', sys_get_temp_dir())),
// ];

return [
    'ffmpeg' => [
        // Jalur biner eksekusi FFmpeg di Ubuntu
        'binaries' => env('FFMPEG_BINARIES', '/usr/bin/ffmpeg'),
        
        'threads'  => 4,   // Opsional: sesuaikan dengan jumlah thread CPU server Anda
    ],

    'ffprobe' => [
        // Jalur biner eksekusi FFprobe di Ubuntu
        'binaries' => env('FFPROBE_BINARIES', '/usr/bin/ffprobe'),
    ],

    'timeout' => 3600, // Menaikkan batas timeout internal FFmpeg menjadi 1 jam

    'enable_logging' => true,

    'set_command_and_error_output_on_exception' => true,
];
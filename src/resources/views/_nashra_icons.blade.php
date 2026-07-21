@php
if (!function_exists('nashra_weather_icon')) {
    function nashra_weather_icon($key) {
        return [
            'sun' => '<g><circle cx="24" cy="24" r="9" fill="#E4B93E"/><g stroke="#C1A45C" stroke-width="2.4" stroke-linecap="round"><line x1="24" y1="6" x2="24" y2="11"/><line x1="24" y1="37" x2="24" y2="42"/><line x1="6" y1="24" x2="11" y2="24"/><line x1="37" y1="24" x2="42" y2="24"/><line x1="11" y1="11" x2="14.5" y2="14.5"/><line x1="33.5" y1="33.5" x2="37" y2="37"/><line x1="37" y1="11" x2="33.5" y2="14.5"/><line x1="11" y1="37" x2="14.5" y2="33.5"/></g></g>',
            'partly' => '<g><circle cx="18" cy="18" r="7" fill="#E4B93E"/><g stroke="#C1A45C" stroke-width="2" stroke-linecap="round"><line x1="18" y1="5" x2="18" y2="9"/><line x1="5" y1="18" x2="9" y2="18"/><line x1="8.5" y1="8.5" x2="11" y2="11"/><line x1="27.5" y1="8.5" x2="25" y2="11"/></g><ellipse cx="28" cy="30" rx="13" ry="8.5" fill="#B7C2B4"/><ellipse cx="24" cy="29" rx="9" ry="6.5" fill="#DFE6DB"/></g>',
            'cloud' => '<g><ellipse cx="20" cy="26" rx="13" ry="9" fill="#B7C2B4"/><ellipse cx="30" cy="28" rx="10" ry="7" fill="#B7C2B4"/><ellipse cx="22" cy="24" rx="11" ry="8" fill="#E1E7DC"/></g>',
            'rain' => '<g><ellipse cx="24" cy="20" rx="14" ry="9" fill="#93A395"/><g stroke="#4E7A63" stroke-width="2.6" stroke-linecap="round"><line x1="16" y1="32" x2="14" y2="40"/><line x1="24" y1="32" x2="22" y2="40"/><line x1="32" y1="32" x2="30" y2="40"/></g></g>',
            'snow' => '<g><ellipse cx="20" cy="26" rx="13" ry="9" fill="#B7C2B4"/><ellipse cx="30" cy="28" rx="10" ry="7" fill="#B7C2B4"/><ellipse cx="22" cy="24" rx="11" ry="8" fill="#E1E7DC"/></g>',
        ][$key] ?? '<g><circle cx="18" cy="18" r="7" fill="#E4B93E"/><g stroke="#C1A45C" stroke-width="2" stroke-linecap="round"><line x1="18" y1="5" x2="18" y2="9"/><line x1="5" y1="18" x2="9" y2="18"/><line x1="8.5" y1="8.5" x2="11" y2="11"/><line x1="27.5" y1="8.5" x2="25" y2="11"/></g><ellipse cx="28" cy="30" rx="13" ry="8.5" fill="#B7C2B4"/><ellipse cx="24" cy="29" rx="9" ry="6.5" fill="#DFE6DB"/></g>';
    }
}
if (!function_exists('nashra_prayer_icon')) {
    function nashra_prayer_icon($name) {
        return [
            'الفجر' => '<g><circle cx="15" cy="15" r="9" fill="#146B3F"/><circle cx="19.5" cy="12.75" r="8.280000000000001" fill="#F9F5EA"/></g>',
            'الشروق' => '<g><path d="M9.5 19 a5.5 5.5 0 0 1 11 0 Z" fill="#E4B93E"/><g stroke="#C1A45C" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none"><path d="M6 19.5 h18"/><path d="M12.5 26 L15 23.5 L17.5 26"/></g></g>',
            'الظهر' => '<g><circle cx="15" cy="15" r="6" fill="#E4B93E"/><g stroke="#C1A45C" stroke-width="1.8" stroke-linecap="round"><line x1="15" y1="4" x2="15" y2="7"/><line x1="15" y1="23" x2="15" y2="26"/><line x1="4" y1="15" x2="7" y2="15"/><line x1="23" y1="15" x2="26" y2="15"/></g></g>',
            'العصر' => '<g><circle cx="15" cy="15" r="5.5" fill="#E4B93E"/><g stroke="#C1A45C" stroke-width="1.6" stroke-linecap="round"><line x1="15" y1="5" x2="15" y2="8"/><line x1="6" y1="15" x2="9" y2="15"/><line x1="21" y1="15" x2="24" y2="15"/><line x1="8" y1="8" x2="10" y2="10"/></g></g>',
            'المغرب' => '<g><path d="M9.5 19 a5.5 5.5 0 0 1 11 0 Z" fill="#C9853C"/><g stroke="#B7791F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none"><path d="M6 19.5 h18"/><path d="M12.5 23.5 L15 26 L17.5 23.5"/></g></g>',
            'العشاء' => '<g><circle cx="12" cy="15" r="8" fill="#146B3F"/><circle cx="16.0" cy="13.0" r="7.36" fill="#F9F5EA"/><g stroke="#C1A45C" stroke-width="1.2"><line x1="22" y1="9" x2="22" y2="14"/><line x1="19.5" y1="11.5" x2="24.5" y2="11.5"/></g></g>',
        ][$name] ?? '<g><circle cx="15" cy="15" r="6" fill="#E4B93E"/><g stroke="#C1A45C" stroke-width="1.8" stroke-linecap="round"><line x1="15" y1="4" x2="15" y2="7"/><line x1="15" y1="23" x2="15" y2="26"/><line x1="4" y1="15" x2="7" y2="15"/><line x1="23" y1="15" x2="26" y2="15"/></g></g>';
    }
}
if (!function_exists('nashra_reco_icon')) {
    function nashra_reco_icon($type) {
        return [
            'restaurant' => '<g stroke="#146B3F" stroke-width="2.2" fill="none" stroke-linecap="round"><line x1="10" y1="6" x2="10" y2="24"/><path d="M6 6 v6 M10 6 v6 M14 6 v6 M6 12 h8"/><line x1="21" y1="6" x2="21" y2="24"/><path d="M21 6 a4 4 0 0 1 0 8"/></g>', 'مطعم' => '<g stroke="#146B3F" stroke-width="2.2" fill="none" stroke-linecap="round"><line x1="10" y1="6" x2="10" y2="24"/><path d="M6 6 v6 M10 6 v6 M14 6 v6 M6 12 h8"/><line x1="21" y1="6" x2="21" y2="24"/><path d="M21 6 a4 4 0 0 1 0 8"/></g>',
            'landmark' => '<g fill="#146B3F"><path d="M15 6 L25 14 L5 14 Z"/><rect x="7" y="15" width="3" height="9"/><rect x="13.5" y="15" width="3" height="9"/><rect x="20" y="15" width="3" height="9"/><rect x="5" y="24" width="20" height="3"/></g>', 'معلم' => '<g fill="#146B3F"><path d="M15 6 L25 14 L5 14 Z"/><rect x="7" y="15" width="3" height="9"/><rect x="13.5" y="15" width="3" height="9"/><rect x="20" y="15" width="3" height="9"/><rect x="5" y="24" width="20" height="3"/></g>',
            'park' => '<g><rect x="13.5" y="17" width="3" height="8" fill="#8A5A2B"/><g fill="#146B3F"><circle cx="15" cy="12" r="7"/><circle cx="9" cy="16" r="5"/><circle cx="21" cy="16" r="5"/></g></g>', 'منتزه' => '<g><rect x="13.5" y="17" width="3" height="8" fill="#8A5A2B"/><g fill="#146B3F"><circle cx="15" cy="12" r="7"/><circle cx="9" cy="16" r="5"/><circle cx="21" cy="16" r="5"/></g></g>',
            'cafe' => '<g stroke="#146B3F" stroke-width="2" fill="none" stroke-linecap="round"><path d="M8 11 l2 14 h9 l2 -14 z"/><path d="M23 13 a4 4 0 0 1 0 7"/><line x1="12" y1="5" x2="12" y2="8"/><line x1="17" y1="5" x2="17" y2="8"/></g>', 'مقهى' => '<g stroke="#146B3F" stroke-width="2" fill="none" stroke-linecap="round"><path d="M8 11 l2 14 h9 l2 -14 z"/><path d="M23 13 a4 4 0 0 1 0 7"/><line x1="12" y1="5" x2="12" y2="8"/><line x1="17" y1="5" x2="17" y2="8"/></g>',
            'other' => '<g stroke="#146B3F" stroke-width="2" fill="none"><path d="M15 25.5 C10.5 20 7.5 16.2 7.5 12.5 a7.5 7.5 0 0 1 15 0 c0 3.7 -3 7.5 -7.5 13 Z"/><circle cx="15" cy="12.5" r="2.6" fill="#146B3F" stroke="none"/></g>', 'أخرى' => '<g stroke="#146B3F" stroke-width="2" fill="none"><path d="M15 25.5 C10.5 20 7.5 16.2 7.5 12.5 a7.5 7.5 0 0 1 15 0 c0 3.7 -3 7.5 -7.5 13 Z"/><circle cx="15" cy="12.5" r="2.6" fill="#146B3F" stroke="none"/></g>',
            'event' => '<g stroke="#146B3F" stroke-width="2" fill="none"><rect x="6" y="8" width="18" height="16" rx="2"/><line x1="6" y1="13" x2="24" y2="13"/><line x1="11" y1="5" x2="11" y2="9"/><line x1="19" y1="5" x2="19" y2="9"/></g><g fill="#146B3F"><rect x="9" y="16" width="3.5" height="3"/><rect x="17.5" y="16" width="3.5" height="3"/></g>',
        ][$type] ?? '<g stroke="#146B3F" stroke-width="2.2" fill="none" stroke-linecap="round"><line x1="10" y1="6" x2="10" y2="24"/><path d="M6 6 v6 M10 6 v6 M14 6 v6 M6 12 h8"/><line x1="21" y1="6" x2="21" y2="24"/><path d="M21 6 a4 4 0 0 1 0 8"/></g>';
    }
}
if (!function_exists('nashra_reco_label')) {
    function nashra_reco_label($type) {
        return ['restaurant'=>'مطعم','landmark'=>'معلم','park'=>'منتزه','cafe'=>'مقهى','other'=>'أخرى'][$type] ?? $type;
    }
}
@endphp
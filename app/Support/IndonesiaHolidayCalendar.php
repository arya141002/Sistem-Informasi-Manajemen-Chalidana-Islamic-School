<?php

namespace App\Support;

use DateTimeImmutable;

class IndonesiaHolidayCalendar
{
    public static function eventsForMonth(int $year, int $month): array
    {
        return array_values(array_filter(
            self::eventsForYear($year),
            fn (array $event) => self::eventTouchesMonth($event, $year, $month)
        ));
    }

    public static function eventsForYear(int $year): array
    {
        return match ($year) {
            2026 => self::events2026(),
            default => [],
        };
    }

    private static function events2026(): array
    {
        return [
            self::single('2026-01-01', 'Tahun Baru 2026 Masehi', 'national_holiday'),
            self::single('2026-01-16', 'Isra Mikraj Nabi Muhammad saw.', 'national_holiday'),
            self::single('2026-02-16', 'Cuti Bersama Tahun Baru Imlek 2577 Kongzili', 'collective_leave'),
            self::single('2026-02-17', 'Tahun Baru Imlek 2577 Kongzili', 'national_holiday'),
            self::single('2026-03-18', 'Cuti Bersama Hari Suci Nyepi', 'collective_leave'),
            self::single('2026-03-19', 'Hari Suci Nyepi Tahun Baru Saka 1948', 'national_holiday'),
            self::single('2026-03-20', 'Cuti Bersama Idulfitri 1447 H', 'collective_leave'),
            self::range('2026-03-21', '2026-03-22', 'Idulfitri 1447 H', 'national_holiday'),
            self::single('2026-03-23', 'Cuti Bersama Idulfitri 1447 H', 'collective_leave'),
            self::single('2026-03-24', 'Cuti Bersama Idulfitri 1447 H', 'collective_leave'),
            self::single('2026-04-03', 'Wafat Yesus Kristus', 'national_holiday'),
            self::single('2026-04-05', 'Kebangkitan Yesus Kristus (Paskah)', 'national_holiday'),
            self::single('2026-05-01', 'Hari Buruh Internasional', 'national_holiday'),
            self::single('2026-05-14', 'Kenaikan Yesus Kristus', 'national_holiday'),
            self::single('2026-05-15', 'Cuti Bersama Kenaikan Yesus Kristus', 'collective_leave'),
            self::single('2026-05-27', 'Iduladha 1447 H', 'national_holiday'),
            self::single('2026-05-28', 'Cuti Bersama Iduladha 1447 H', 'collective_leave'),
            self::single('2026-05-31', 'Hari Raya Waisak 2570 BE', 'national_holiday'),
            self::single('2026-06-01', 'Hari Lahir Pancasila', 'national_holiday'),
            self::single('2026-06-16', '1 Muharam Tahun Baru Islam 1448 H', 'national_holiday'),
            self::single('2026-08-17', 'Proklamasi Kemerdekaan', 'national_holiday'),
            self::single('2026-08-25', 'Maulid Nabi Muhammad saw.', 'national_holiday'),
            self::single('2026-12-24', 'Cuti Bersama Kelahiran Yesus Kristus', 'collective_leave'),
            self::single('2026-12-25', 'Kelahiran Yesus Kristus', 'national_holiday'),
        ];
    }

    private static function single(string $date, string $title, string $type): array
    {
        return self::range($date, $date, $title, $type);
    }

    private static function range(string $startDate, string $endDate, string $title, string $type): array
    {
        return [
            'id' => 'id-holiday-' . $startDate . '-' . $type,
            'title' => $title,
            'description' => $type === 'collective_leave' ? 'Cuti bersama nasional' : 'Hari libur nasional',
            'event_date' => $startDate,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'campus' => null,
            'branch' => null,
            'is_holiday' => true,
            'holiday_type' => $type,
        ];
    }

    private static function eventTouchesMonth(array $event, int $year, int $month): bool
    {
        $monthStart = new DateTimeImmutable(sprintf('%04d-%02d-01', $year, $month));
        $monthEnd = $monthStart->modify('last day of this month');
        $eventStart = new DateTimeImmutable($event['start_date']);
        $eventEnd = new DateTimeImmutable($event['end_date']);

        return $eventStart <= $monthEnd && $eventEnd >= $monthStart;
    }
}

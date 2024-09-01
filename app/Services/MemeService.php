<?php

namespace App\Services;

use Intervention\Image\ImageManager;

class MemeService
{
    protected $imageManager;

    public function __construct()
    {
        // Создаем ImageManager с использованием конфигурационного массива
        $this->imageManager = new ImageManager(['driver' => 'gd']); // или 'imagick'
    }

    public function createMeme($imagePath, $caption)
    {
        // Создаем изображение с помощью ImageManager
        $image = $this->imageManager->make($imagePath);

        // Путь к шрифту Impact
        $fontPath = public_path('fonts/impact.ttf'); // Убедитесь, что этот путь правильный и шрифт существует

        // Получаем размеры изображения
        $imageWidth = $image->width();
        $imageHeight = $image->height();

        // Размер шрифта и координаты текста
        $fontSize = min($imageWidth, $imageHeight) * 0.08; // Шрифт 8% от наибольшей стороны изображения
        $textColor = '#ffffff'; // Цвет основного текста
        $outlineColor = '#000000'; // Цвет обводки (черный)

        // Определяем координаты для текста
        $x = $imageWidth / 2; // Центр по горизонтали
        $y = $imageHeight * 0.05; // Отступ 5% от высоты изображения

        // Добавляем черную обводку
        for ($i = -2; $i <= 2; $i++) {
            for ($j = -2; $j <= 2; $j++) {
                if ($i != 0 || $j != 0) {
                    $image->text($caption, $x + $i, $y + $j, function($font) use ($fontPath, $fontSize, $outlineColor) {
                        $font->file($fontPath); // Указываем путь к файлу шрифта
                        $font->size($fontSize);
                        $font->color($outlineColor);
                        $font->align('center'); // Выравнивание по горизонтали
                        $font->valign('top'); // Выравнивание по вертикали
                    });
                }
            }
        }

        // Добавляем основной текст поверх обводки
        $image->text($caption, $x, $y, function($font) use ($fontPath, $fontSize, $textColor) {
            $font->file($fontPath); // Указываем путь к файлу шрифта
            $font->size($fontSize);
            $font->color($textColor);
            $font->align('center'); // Выравнивание по горизонтали
            $font->valign('top'); // Выравнивание по вертикали
        });

        // Путь для сохранения нового изображения
        $memePath = public_path('tmp/meme_' . time() . '.jpg');

        // Сохраняем изображение
        $image->save($memePath);

        return $memePath;
    }
}

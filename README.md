# dinoweb
# Dino Adventure - Parkur Edition

Jednoduchá plošinovka v prohlížeči. Skáčeš jako dinosaurus přes kaktusy, sbíráš mince a na konci porazíš bosse.

## Jak to spustit

1. Dej všechny soubory do jedný složky (PHP soubor + obrázky + zvuky)
2. Musíš mít nahraný na server s PHP (kvůli tomu $assets nahoře), nebo si to klidně přejmenuj na .html, funguje to i tak
3. Otevři v prohlížeči

## Co potřebuješ mít ve složce

Obrázky:
- dino.png, dino1.png (dino doprava/doleva)
- boss.png
- coin.png
- kasktus1.png (nepřítel/kaktus)
- meteor.png
- door.png
- nebo.png (pozadí ve hře)
- fondino.png (pozadí v menu)

Zvuky:
- gamesong.mp3
- boss.mp3
- coinsi.mp3

Pokud nějaký obrázek chybí, hra to nespadne - místo obrázku se vykreslí barevný čtverec, takže se dá i takhle hrát/testovat.

## Ovládání

- A / D - pohyb doleva/doprava
- SPACE - skok
- po prohře nebo výhře: X - restart, M - menu

## Levely

1. - 3. normální levely, projdeš přes dveře na konci
4. - boss fight, musíš na bosse skákat shora (jako na hříbek), má 10 životů
5. - "vlak" level (zatím stejný jako ostatní, jen jiný label)

## Hudba

Jde vypnout tlačítkem v menu nebo dole pod hrou. Ve hře hraje jiná znělka než u bosse.

## Poznámka

Celá hra běží na jednom canvasu 800x400, žádné knihovny, čistý JS. Kolize jsou obyčejné obdélníky (AABB).

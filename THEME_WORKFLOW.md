# Theme workflow

This repo now keeps explicit theme snapshots for the main marketing surface.

## Controlled files
- index.html
- legal.html
- performance.html
- hevi.html
- applications.html
- styles.css

## Theme sources
- `theme-variants/light/`
- `theme-variants/dark/`

## Apply a theme
```bash
./scripts/switch-theme.sh light
./scripts/switch-theme.sh dark
```

## Rule
Do not manually half-edit live theme files when switching.
Always update the variant snapshot first, then apply it with the script.

## Current baseline
- light baseline commit: `878cd6f` (`Fresh website rebuild baseline`)
- dark baseline: pending creation

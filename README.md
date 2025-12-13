# Starfield Load Order

This repository is used to keep track of changes to my Starfield load order in `Plugins.txt`.

I check this out locally into `%AppData%\Local\Starfield`, which allows me to view any changes
made to the file and choose what to do with them comfortably from [GitHub Desktop](https://github.com/apps/desktop).

## Load order principles

- Keep mods that add items in the same load order position: The item IDs are directly related
  to the load order. Let's say that you use a mod that adds a new spacesuit, and it is Nr #5
  in your load order. If you install a mod above it, and it is suddenly at Nr #6, the spacesuit
  will no longer be available. 

## Mod List

[View the full mod list](/docs/starfield-mods.md)

Details on the mods and the Vortex mod list can be found under [docs](/docs). These are generated
automatically using my [Vortext Modlist Exporter](https://github.com/Mistralys/vortex-modlist-exporter)
tool.

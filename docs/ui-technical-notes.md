# UI technical notes

## Navigation

- Desktop submenus must never affect header height. Position them absolutely relative to the top-level parent and hide them with opacity, visibility and pointer-events.
- Support both pointer and keyboard navigation using `:hover` together with `:focus-within`.
- Add a small invisible hover bridge between the parent link and dropdown so the menu does not close while the pointer crosses the gap.
- At the mobile breakpoint, reset dropdown positioning and visibility so child links stay in the normal document flow.


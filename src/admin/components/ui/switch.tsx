import * as React from "react"
import { Switch as SwitchPrimitive } from "radix-ui"

import { cn } from "@/lib/utils"

function Switch({
  className,
  size = "default",
  ...props
}: React.ComponentProps<typeof SwitchPrimitive.Root> & {
  size?: "sm" | "default"
}) {
  return (
    <SwitchPrimitive.Root
      data-slot="switch"
      data-size={size}
      className={cn(
        "apbl:peer apbl:group/switch apbl:inline-flex apbl:shrink-0 apbl:items-center apbl:rounded-full apbl:border apbl:border-transparent apbl:shadow-xs apbl:transition-all apbl:outline-none apbl:focus-visible:border-ring apbl:focus-visible:ring-[3px] apbl:focus-visible:ring-ring/50 apbl:disabled:cursor-not-allowed apbl:disabled:opacity-50 apbl:data-[size=default]:h-[1.15rem] apbl:data-[size=default]:w-8 apbl:data-[size=sm]:h-3.5 apbl:data-[size=sm]:w-6 apbl:data-[state=checked]:bg-primary apbl:data-[state=unchecked]:bg-input apbl:dark:data-[state=unchecked]:bg-input/80",
        className
      )}
      {...props}
    >
      <SwitchPrimitive.Thumb
        data-slot="switch-thumb"
        className={cn(
          "apbl:pointer-events-none apbl:block apbl:rounded-full apbl:bg-background apbl:ring-0 apbl:transition-transform apbl:group-data-[size=default]/switch:size-4 apbl:group-data-[size=sm]/switch:size-3 apbl:data-[state=checked]:translate-x-[calc(100%-2px)] apbl:data-[state=unchecked]:translate-x-0 apbl:dark:data-[state=checked]:bg-primary-foreground apbl:dark:data-[state=unchecked]:bg-foreground"
        )}
      />
    </SwitchPrimitive.Root>
  )
}

export { Switch }

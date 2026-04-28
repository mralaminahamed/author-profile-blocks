import * as React from "react"
import { Label as LabelPrimitive } from "radix-ui"

import { cn } from "@/lib/utils"

function Label({
  className,
  ...props
}: React.ComponentProps<typeof LabelPrimitive.Root>) {
  return (
    <LabelPrimitive.Root
      data-slot="label"
      className={cn(
        "apbl:flex apbl:items-center apbl:gap-2 apbl:text-sm apbl:leading-none apbl:font-medium apbl:select-none apbl:group-data-[disabled=true]:pointer-events-none apbl:group-data-[disabled=true]:opacity-50 apbl:peer-disabled:cursor-not-allowed apbl:peer-disabled:opacity-50",
        className
      )}
      {...props}
    />
  )
}

export { Label }
